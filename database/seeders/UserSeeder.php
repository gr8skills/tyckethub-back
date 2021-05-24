<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
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
        User::factory(30)->create()->each(function ($user) {
            $rnd_role_id = random_int(1, Role::all()->count());

            $user->roles()->sync($rnd_role_id);
        });
    }
}
