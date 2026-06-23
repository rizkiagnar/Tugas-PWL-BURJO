<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Customer extends BaseController
{
    protected $menuModel;
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session();
        }
    }

    /**
     * Display Menu List & Cart Sidebar
     */
    public function index()
    {
        $menus = $this->menuModel->where('is_available', 1)->findAll();
        
        $data = [
            'title' => 'Menu Warung Makan',
            'menus' => $menus
        ];

        return view('customer/menu', $data);
    }

    /**
     * Review order details before checkout
     */
    public function checkout()
    {
        $customerName = $this->request->getPost('customer_name');
        $tableNumber  = $this->request->getPost('table_number');
        $cartJson     = $this->request->getPost('cart_data');

        if (empty($customerName) || empty($tableNumber) || empty($cartJson)) {
            return redirect()->to(base_url())->with('error', 'Silakan pilih menu makanan dan nomor meja terlebih dahulu.');
        }

        $cartItems = json_decode($cartJson, true);
        if (empty($cartItems)) {
            return redirect()->to(base_url())->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Calculate and verify total prices on server-side
        $totalPrice = 0;
        foreach ($cartItems as &$item) {
            $dbItem = $this->menuModel->find($item['id']);
            if ($dbItem) {
                // Ensure price matches the database
                $item['price'] = $dbItem['price'];
                $totalPrice += $dbItem['price'] * $item['qty'];
            }
        }

        $data = [
            'title' => 'Verifikasi Pesanan',
            'customer_name' => $customerName,
            'table_number' => $tableNumber,
            'total_price' => $totalPrice,
            'cart_items' => $cartItems
        ];

        return view('customer/checkout_verify', $data);
    }

    /**
     * Save order details to Database
     */
    public function confirm_order()
    {
        $customerName  = $this->request->getPost('customer_name');
        $tableNumber   = $this->request->getPost('table_number');
        $totalPrice    = $this->request->getPost('total_price');
        $paymentMethod = $this->request->getPost('payment_method');
        $cartJson      = $this->request->getPost('cart_data');

        if (empty($customerName) || empty($tableNumber) || empty($paymentMethod) || empty($cartJson)) {
            return redirect()->to(base_url())->with('error', 'Data pesanan tidak lengkap.');
        }

        $cartItems = json_decode($cartJson, true);
        if (empty($cartItems)) {
            return redirect()->to(base_url())->with('error', 'Pesanan Anda kosong.');
        }

        // 1. Save into orders table
        $orderData = [
            'table_number'   => (int)$tableNumber,
            'customer_name'  => $customerName,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'order_status'   => 'pending',
            'total_price'    => (int)$totalPrice,
        ];

        if (!$this->orderModel->save($orderData)) {
            return redirect()->to(base_url())->with('error', 'Gagal memproses pesanan Anda.');
        }

        $orderId = $this->orderModel->getInsertID();

        // 2. Save items into order_items table
        foreach ($cartItems as $item) {
            $itemData = [
                'order_id' => $orderId,
                'menu_id'  => $item['id'],
                'quantity' => $item['qty'],
                'price'    => $item['price'],
                'notes'    => $item['notes'] ?? ''
            ];
            $this->orderItemModel->save($itemData);
        }

        // Redirect to payment instructions page
        return redirect()->to(base_url('customer/payment/' . $orderId));
    }

    /**
     * Show Payment QRIS / Cash screen
     */
    public function payment($orderId = null)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return redirect()->to(base_url())->with('error', 'Pesanan tidak ditemukan.');
        }

        $data = [
            'title' => 'Pembayaran Pesanan',
            'order' => $order
        ];

        return view('customer/payment', $data);
    }

    /**
     * Simulate QRIS Payment Success
     */
    public function simulate_payment($orderId = null)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return redirect()->to(base_url())->with('error', 'Pesanan tidak ditemukan.');
        }

        // Update payment status to paid
        $this->orderModel->update($orderId, [
            'payment_status' => 'paid'
        ]);

        return redirect()->to(base_url('customer/receipt/' . $orderId))->with('success', 'Pembayaran QRIS berhasil disimulasikan!');
    }

    /**
     * Display final Receipt invoice
     */
    public function receipt($orderId = null)
    {
        $order = $this->orderModel->find($orderId);
        if (!$order) {
            return redirect()->to(base_url())->with('error', 'Pesanan tidak ditemukan.');
        }

        $items = $this->orderItemModel->getItemsWithMenu($orderId);

        $data = [
            'title' => 'Struk Pembelian #' . $orderId,
            'order' => $order,
            'items' => $items
        ];

        return view('customer/receipt', $data);
    }
}
