<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //For testing purpose
        User::updateOrCreate(
            ['email' => 'ahmad.saeed@gmail.com'],
            [
                'email' => 'ahmad.saeed@gmail.com',
                'name' => 'Ahmad Saeed',
                'password' => '12345678'
            ]);
    }
}
