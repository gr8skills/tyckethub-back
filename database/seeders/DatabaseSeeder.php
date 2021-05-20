<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        User::truncate();


        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
//            PermissionRoleSeeder::class,
        ]);

    }
}
