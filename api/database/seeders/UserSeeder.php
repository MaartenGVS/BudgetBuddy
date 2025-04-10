<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Maarten Van Santen',
            'email' => 'vansantenmaarten@budgetbuddy.be',
            'password' => hash::make('welcome'),
            'is_admin' => 1,
        ]);
    }
}
