<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Creates (or updates) the default manager account.
     * Login: admin / ChangeMe@123  then OTP 123456 (test mode).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'المدير',
                'phone'    => '0500000000',
                'role'     => 'manager',
                'password' => 'ChangeMe@123', // hashed via model cast
            ]
        );
    }
}
