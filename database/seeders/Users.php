<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::insert(array([
            'name' => '管理者',
            'administer_flag' => 1,
            'email' => 'administer@example.com',
            'password' => Hash::make('pass'),
        ] , [
            'name' => '利用者1',
            'administer_flag' => 0,
            'email' => 'user1@example.com',
            'password' => Hash::make('pass'),
        ] , [
            'name' => '利用者2',
            'administer_flag' => 0,
            'email' => 'user2@example.com',
            'password' => Hash::make('pass'),
        ] , [
            'name' => '利用者3',
            'administer_flag' => 0,
            'email' => 'user3@example.com',
            'password' => Hash::make('pass'),
        ]));
    }
}
