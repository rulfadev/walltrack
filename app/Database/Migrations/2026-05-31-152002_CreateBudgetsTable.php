<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetsTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('budgets')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'category_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                ],
                'month' => [
                    'type' => 'TINYINT',
                    'constraint' => 2,
                ],
                'year' => [
                    'type' => 'INT',
                    'constraint' => 4,
                ],
                'amount' => [
                    'type' => 'DECIMAL',
                    'constraint' => '14,2',
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
            $this->forge->addKey(['user_id', 'category_id', 'month', 'year']);
            $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('category_id', 'categories', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('budgets');
        }
    }

    public function down()
    {
        $this->forge->dropTable('budgets', true);
    }
}