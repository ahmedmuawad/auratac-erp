<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Creates (or updates) the default super-admin (manager) account.
     * Login: admin / ChangeMe@123  then OTP 123456 (test mode).
     */
    public function run(): void
    {
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['display_name' => 'المدير العام']);

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'المدير',
                'phone'    => '0500000000',
                'role'     => 'manager',
                'role_id'  => $managerRole->id,
                'password' => 'ChangeMe@123', // hashed via model cast
            ]
        );
    }
}
