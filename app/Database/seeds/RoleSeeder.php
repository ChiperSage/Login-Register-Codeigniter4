<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'role_name' => 'Admin',
            ],
            [
                'role_name' => 'User',
            ],
            [
                'role_name' => 'Manager',
            ],
            [
                'role_name' => 'Guest',
            ]
        ];

        // Using Query Builder to insert data
        $this->db->table('roles')->insertBatch($data);
    }
}
