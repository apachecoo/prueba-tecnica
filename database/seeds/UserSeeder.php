<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Empleado prueba 1',
            'email' => 'empleado1@prueba.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => date('Y-m-d h:i:s')
        ]);
    }
}
