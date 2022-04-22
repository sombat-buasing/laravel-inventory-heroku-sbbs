<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        
        $data = [
            'fullname' => 'Samit Koyom',
            'username' => 'iamsamit',
            'email' => 'samit@email.com',
            'password' => Hash::make('123456'),
            'tel' => '0987654321',
            'avatar' => 'https://via.placeholde.com/400x400.png/005429?text=udses',
            'role' => '1',
            'remember_token' => '1098763645'
        ];

        User::create($data);

        User::factory(49)->create();

    }
}
