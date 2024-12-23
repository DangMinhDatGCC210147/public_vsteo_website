<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@fe.edu.vn',
                'account_id' => 'FGWCT01',
                'role' => '0',
                'password' => Hash::make('12345678'),
            ],


            // Add more sample users as needed
        ];

        // Insert the sample data into the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
