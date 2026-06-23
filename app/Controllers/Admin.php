<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Admin extends BaseController
{
    protected $menuModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        
        if (session_status() == PHP_SESSION_NONE) {
            session();
        }
    }

    /**
     * Check if admin is authenticated
     */
    private function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return false;
        }
        return true;
    }

    /**
     * Admin login page
     */
    public function login()
    {
        if ($this->checkAuth()) {
            return redirect()->to(base_url('admin/orders'));
        }
        return view('admin/login');
    }

    /**
     * Handle Login Credentials validation
     */
    public function login_submit()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Hardcoded admin login credentials (admin / admin123)
        if ($username === 'admin' && $password === 'admin123') {
            session()->set([
                'admin_logged_in' => true,
                'admin_username'  => 'admin'
            ]);
            return redirect()->to(base_url('admin/orders'))->with('success', 'Selamat datang kembali, Admin!');
        }

        return redirect()->to(base_url('admin'))->with('error', 'Username atau Password salah.');
    }

    /**
     * Admin logout
     */
    public function logout()
    {
        session()->remove('admin_logged_in');
        session()->remove('admin_username');
        return redirect()->to(base_url())->with('success', 'Anda telah keluar dari Admin Panel.');
    }

    /**
     * Dashboard: Orders Tracking
     */
    public function orders()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        // Fetch all orders
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();
        
        // Fetch items for each order
        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemModel->getItemsWithMenu($order['id']);
        }

        // Compile statistics
        $stats = [
            'pending'   => $this->orderModel->where('order_status', 'pending')->countAllResults(),
            'cooking'   => $this->orderModel->where('order_status', 'cooking')->countAllResults(),
            'completed' => $this->orderModel->where('order_status', 'completed')->countAllResults(),
            'revenue'   => $this->orderModel->where('payment_status', 'paid')->selectSum('total_price')->first()['total_price'] ?? 0
        ];

        $data = [
            'title'  => 'Kelola Pesanan',
            'orders' => $orders,
            'stats'  => $stats
        ];

        return view('admin/orders', $data);
    }

    /**
     * Update order payment status (pending/paid)
     */
    public function update_payment($id = null)
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $paymentStatus = $this->request->getPost('payment_status');
        if (in_array($paymentStatus, ['pending', 'paid'])) {
            $this->orderModel->update($id, ['payment_status' => $paymentStatus]);
            return redirect()->back()->with('success', 'Status pembayaran pesanan #' . $id . ' diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status pembayaran.');
    }

    /**
     * Update order serving status (pending/cooking/completed/cancelled)
     */
    public function update_order_status($id = null)
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $orderStatus = $this->request->getPost('order_status');
        if (in_array($orderStatus, ['pending', 'cooking', 'completed', 'cancelled'])) {
            
            // Auto update payment if marked completed
            $updateData = ['order_status' => $orderStatus];
            if ($orderStatus === 'completed') {
                $updateData['payment_status'] = 'paid';
            }
            
            $this->orderModel->update($id, $updateData);
            return redirect()->back()->with('success', 'Status pelayanan pesanan #' . $id . ' diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status pelayanan.');
    }

    /**
     * CRUD: Menus list view
     */
    public function menus()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $menus = $this->menuModel->orderBy('type', 'ASC')->orderBy('name', 'ASC')->findAll();

        $data = [
            'title' => 'Kelola Data Menu',
            'menus' => $menus
        ];

        return view('admin/menu_list', $data);
    }

    /**
     * CRUD: Create Menu Form
     */
    public function menu_create()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $data = [
            'title' => 'Tambah Menu Baru'
        ];

        return view('admin/menu_form', $data);
    }

    /**
     * CRUD: Store Menu in DB
     */
    public function menu_store()
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $name        = $this->request->getPost('name');
        $type        = $this->request->getPost('type');
        $price       = $this->request->getPost('price');
        $isAvailable = $this->request->getPost('is_available');
        $description = $this->request->getPost('description');

        $imageFile   = $this->request->getFile('image');
        $imageName   = null;

        // Image upload handling
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Validation
            if (!in_array($imageFile->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
                return redirect()->back()->withInput()->with('error', 'Format gambar harus JPG, JPEG, PNG, atau WEBP.');
            }
            if ($imageFile->getSizeByUnit('mb') > 2) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal adalah 2MB.');
            }

            // Create target directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/menus/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
        }

        $menuData = [
            'name'         => $name,
            'type'         => $type,
            'price'        => (int)$price,
            'is_available' => (int)$isAvailable,
            'description'  => $description,
            'image'        => $imageName
        ];

        if ($this->menuModel->save($menuData)) {
            return redirect()->to(base_url('admin/menus'))->with('success', 'Menu baru "' . $name . '" berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan menu baru.');
    }

    /**
     * CRUD: Edit Menu Form
     */
    public function menu_edit($id = null)
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to(base_url('admin/menus'))->with('error', 'Menu tidak ditemukan.');
        }

        $data = [
            'title' => 'Ubah Menu - ' . $menu['name'],
            'menu'  => $menu
        ];

        return view('admin/menu_form', $data);
    }

    /**
     * CRUD: Update Menu in DB
     */
    public function menu_update($id = null)
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to(base_url('admin/menus'))->with('error', 'Menu tidak ditemukan.');
        }

        $name        = $this->request->getPost('name');
        $type        = $this->request->getPost('type');
        $price       = $this->request->getPost('price');
        $isAvailable = $this->request->getPost('is_available');
        $description = $this->request->getPost('description');

        $imageFile   = $this->request->getFile('image');
        $imageName   = $menu['image']; // Keep current image

        // Image upload handling
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            if (!in_array($imageFile->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])) {
                return redirect()->back()->withInput()->with('error', 'Format gambar harus JPG, JPEG, PNG, atau WEBP.');
            }
            if ($imageFile->getSizeByUnit('mb') > 2) {
                return redirect()->back()->withInput()->with('error', 'Ukuran gambar maksimal adalah 2MB.');
            }

            // Create target directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/menus/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old file if it exists
            if (!empty($menu['image']) && file_exists($uploadPath . $menu['image'])) {
                unlink($uploadPath . $menu['image']);
            }

            $imageName = $imageFile->getRandomName();
            $imageFile->move($uploadPath, $imageName);
        }

        $menuData = [
            'id'           => $id,
            'name'         => $name,
            'type'         => $type,
            'price'        => (int)$price,
            'is_available' => (int)$isAvailable,
            'description'  => $description,
            'image'        => $imageName
        ];

        if ($this->menuModel->save($menuData)) {
            return redirect()->to(base_url('admin/menus'))->with('success', 'Menu "' . $name . '" berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui menu.');
    }

    /**
     * CRUD: Delete Menu from DB
     */
    public function menu_delete($id = null)
    {
        if (!$this->checkAuth()) {
            return redirect()->to(base_url('admin'))->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to(base_url('admin/menus'))->with('error', 'Menu tidak ditemukan.');
        }

        // Delete image file if exists
        if (!empty($menu['image'])) {
            $imagePath = FCPATH . 'uploads/menus/' . $menu['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Delete from DB
        $this->menuModel->delete($id);
        return redirect()->to(base_url('admin/menus'))->with('success', 'Menu "' . $menu['name'] . '" berhasil dihapus.');
    }
}
