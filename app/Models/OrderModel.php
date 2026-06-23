<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'table_number', 
        'customer_name', 
        'payment_method', 
        'payment_status', 
        'order_status', 
        'total_price'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'table_number'   => 'required|integer|greater_than_equal_to[1]',
        'customer_name'  => 'required|min_length[2]|max_length[255]',
        'payment_method' => 'required|in_list[cash,qris]',
        'payment_status' => 'required|in_list[pending,paid]',
        'order_status'   => 'required|in_list[pending,cooking,completed,cancelled]',
        'total_price'    => 'required|numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
