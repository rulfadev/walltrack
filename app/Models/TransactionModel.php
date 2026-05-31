<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table          = 'transactions';
    protected $primaryKey     = 'id';
    protected $returnType     = 'array';
    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'user_id',
        'wallet_id',
        'transfer_to_wallet_id',
        'category_id',
        'type',
        'amount',
        'description',
        'transaction_date',
        'deleted_at',
    ];
}