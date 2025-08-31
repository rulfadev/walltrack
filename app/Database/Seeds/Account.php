<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Account extends Seeder
{
    public function run()
    {
        //
        $data = [
            'username' => 'user',
            'password' => password_hash('user123', PASSWORD_BCRYPT),
            'email' => 'user@rulfadev.my.id'
        ];

        $this->db->table('users')->insert($data);
    }
}
