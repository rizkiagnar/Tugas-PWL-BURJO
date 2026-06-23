<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['order_id', 'menu_id', 'quantity', 'price', 'notes'];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'order_id' => 'required|integer',
        'menu_id'  => 'required|integer',
        'quantity' => 'required|integer|greater_than[0]',
        'price'    => 'required|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get order items with their menu names and details.
     */
    public function getItemsWithMenu($orderId)
    {
        return $this->select('order_items.*, menus.name as menu_name, menus.type as menu_type, menus.image as menu_image')
                    ->join('menus', 'menus.id = order_items.menu_id')
                    ->where('order_id', $orderId)
                    ->findAll();
    }
}
