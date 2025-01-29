<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'category_id', 'amount', 'description', 'transaction_date'];
    protected $useTimestamps = true;
}
