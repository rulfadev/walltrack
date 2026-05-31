<?php

namespace App\Models;

use CodeIgniter\Model;

class WalletModel extends Model
{
    protected $table            = 'wallets';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'user_id',
        'name',
        'type',
        'initial_balance',
        'current_balance',
        'color',
        'icon',
        'is_default',
        'deleted_at',
    ];
}