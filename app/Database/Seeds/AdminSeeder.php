<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'   => 'admin',
            'password'   => password_hash('admin123', PASSWORD_DEFAULT),
            'name'       => 'Administrator Utama',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Check if admin already exists
        $existing = $this->db->table('admins')->where('username', 'admin')->get()->getRow();
        
        if (!$existing) {
            $this->db->table('admins')->insert($data);
            echo "Default admin created: admin / admin123\n";
        } else {
            echo "Admin already exists, skipping...\n";
        }
    }
}
