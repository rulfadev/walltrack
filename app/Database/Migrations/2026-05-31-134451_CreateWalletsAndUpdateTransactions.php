<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWalletsAndUpdateTransactions extends Migration
{
    public function up()
    {
        /*
         * 1. Buat tabel wallets
         */
        if (!$this->db->tableExists('wallets')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['cash', 'bank', 'ewallet', 'saving'],
                    'default'    => 'cash',
                ],
                'initial_balance' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '14,2',
                    'default'    => 0,
                ],
                'current_balance' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '14,2',
                    'default'    => 0,
                ],
                'color' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => '#134686',
                ],
                'icon' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 50,
                    'default'    => 'bi-wallet2',
                ],
                'is_default' => [
                    'type'    => 'TINYINT',
                    'default' => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('wallets');
        }

        /*
         * 2. Tambah deleted_at ke categories agar kategori tidak hard delete.
         */
        if (!$this->db->fieldExists('deleted_at', 'categories')) {
            $this->forge->addColumn('categories', [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }

        /*
         * 3. Update struktur transactions.
         */
        if (!$this->db->fieldExists('wallet_id', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'wallet_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'user_id',
                ],
            ]);
        }

        if (!$this->db->fieldExists('transfer_to_wallet_id', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'transfer_to_wallet_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'wallet_id',
                ],
            ]);
        }

        if (!$this->db->fieldExists('deleted_at', 'transactions')) {
            $this->forge->addColumn('transactions', [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
        }

        $this->forge->modifyColumn('transactions', [
            'category_id' => [
                'name'       => 'category_id',
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'amount' => [
                'name'       => 'amount',
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
            ],
        ]);

        /*
         * 4. Ubah enum lama debit/kredit ke income/expense/transfer.
         */
        $this->db->query("
            ALTER TABLE transactions 
            MODIFY type ENUM('debit', 'kredit', 'income', 'expense', 'transfer') 
            NOT NULL DEFAULT 'expense'
        ");

        $this->db->table('transactions')
            ->where('type', 'debit')
            ->update(['type' => 'income']);

        $this->db->table('transactions')
            ->where('type', 'kredit')
            ->update(['type' => 'expense']);

        $this->db->query("
            ALTER TABLE transactions 
            MODIFY type ENUM('income', 'expense', 'transfer') 
            NOT NULL DEFAULT 'expense'
        ");

        /*
         * 5. Buat wallet default untuk user lama dan hubungkan transaksi lama.
         */
        $users = $this->db->table('users')->select('id')->get()->getResultArray();

        foreach ($users as $user) {
            $userId = $user['id'];

            $wallet = $this->db->table('wallets')
                ->where('user_id', $userId)
                ->where('deleted_at', null)
                ->get()
                ->getRowArray();

            if (!$wallet) {
                $this->db->table('wallets')->insert([
                    'user_id'         => $userId,
                    'name'            => 'Dompet Utama',
                    'type'            => 'cash',
                    'initial_balance' => 0,
                    'current_balance' => 0,
                    'color'           => '#134686',
                    'icon'            => 'bi-wallet2',
                    'is_default'      => 1,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

                $walletId = $this->db->insertID();
            } else {
                $walletId = $wallet['id'];
            }

            $this->db->table('transactions')
                ->where('user_id', $userId)
                ->where('wallet_id', null)
                ->update(['wallet_id' => $walletId]);

            $income = $this->db->table('transactions')
                ->selectSum('amount')
                ->where('user_id', $userId)
                ->where('wallet_id', $walletId)
                ->where('type', 'income')
                ->where('deleted_at', null)
                ->get()
                ->getRow()
                ->amount ?? 0;

            $expense = $this->db->table('transactions')
                ->selectSum('amount')
                ->where('user_id', $userId)
                ->where('wallet_id', $walletId)
                ->where('type', 'expense')
                ->where('deleted_at', null)
                ->get()
                ->getRow()
                ->amount ?? 0;

            $balance = (float) $income - (float) $expense;

            $this->db->table('wallets')
                ->where('id', $walletId)
                ->update([
                    'current_balance' => $balance,
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);
        }
    }

    public function down()
    {
        $this->db->query("
            ALTER TABLE transactions 
            MODIFY type ENUM('income', 'expense', 'transfer', 'debit', 'kredit') 
            NOT NULL DEFAULT 'expense'
        ");

        $this->db->table('transactions')
            ->where('type', 'income')
            ->update(['type' => 'debit']);

        $this->db->table('transactions')
            ->whereIn('type', ['expense', 'transfer'])
            ->update(['type' => 'kredit']);

        $this->db->query("
            ALTER TABLE transactions 
            MODIFY type ENUM('debit', 'kredit') 
            NOT NULL DEFAULT 'debit'
        ");

        if ($this->db->fieldExists('wallet_id', 'transactions')) {
            $this->forge->dropColumn('transactions', 'wallet_id');
        }

        if ($this->db->fieldExists('transfer_to_wallet_id', 'transactions')) {
            $this->forge->dropColumn('transactions', 'transfer_to_wallet_id');
        }

        if ($this->db->fieldExists('deleted_at', 'transactions')) {
            $this->forge->dropColumn('transactions', 'deleted_at');
        }

        if ($this->db->fieldExists('deleted_at', 'categories')) {
            $this->forge->dropColumn('categories', 'deleted_at');
        }

        $this->forge->dropTable('wallets', true);
    }
}