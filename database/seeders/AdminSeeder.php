<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super.admin@tyckethub.io',
            'phone' => '08067221825',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
