<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'John',
            'lastname' => 'Doe',
            'phone'    => '123456789',
            'password' => bcrypt('12345678'),
            'email'    => 'admin@example.com'
        ])->assignRole('admin');
        
        User::create([
            'name'     => 'Peter',
            'lastname' => 'Kit',
            'phone'    => '123456789',
            'password' => bcrypt('12345678'),
            'email'    => 'basic@example.com'
        ])->assignRole('basic');
        
        User::create([
            'name'     => 'Juan',
            'lastname' => 'Dae',
            'phone'    => '123456789',
            'password' => bcrypt('12345678'),
            'email'    => 'normal@example.com'
        ])->assignRole('normal');
    }
}
