<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTypeIconColorToCategories extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('type', 'categories')) {
            $this->forge->addColumn('categories', [
                'type' => [
                    'type' => 'ENUM',
                    'constraint' => ['income', 'expense'],
                    'default' => 'expense',
                    'after' => 'name',
                ],
            ]);
        }

        if (!$this->db->fieldExists('icon', 'categories')) {
            $this->forge->addColumn('categories', [
                'icon' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'default' => 'bi-tag',
                    'after' => 'type',
                ],
            ]);
        }

        if (!$this->db->fieldExists('color', 'categories')) {
            $this->forge->addColumn('categories', [
                'color' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => '#134686',
                    'after' => 'icon',
                ],
            ]);
        }

        // Kategori lama otomatis dianggap Pengeluaran.
        $this->db->table('categories')
            ->where('type IS NULL', null, false)
            ->orWhere('type', '')
            ->update([
                'type' => 'expense',
                'icon' => 'bi-tag',
                'color' => '#134686',
            ]);
    }

    public function down()
    {
        if ($this->db->fieldExists('color', 'categories')) {
            $this->forge->dropColumn('categories', 'color');
        }

        if ($this->db->fieldExists('icon', 'categories')) {
            $this->forge->dropColumn('categories', 'icon');
        }

        if ($this->db->fieldExists('type', 'categories')) {
            $this->forge->dropColumn('categories', 'type');
        }
    }
}