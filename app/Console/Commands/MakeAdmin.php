<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin {username=admin} {password=ChangeMe@123}';

    protected $description = 'Create or reset the super-admin (manager) account';

    public function handle(): int
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        // Ensure the manager role exists (manager = all permissions via HasPermissions)
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['display_name' => 'المدير العام']);

        $user = User::updateOrCreate(
            ['username' => $username],
            [
                'name'     => 'المدير',
                'phone'    => '0500000000',
                'role'     => 'manager',
                'role_id'  => $managerRole->id,
                'password' => $password, // hashed by the model cast
            ]
        );

        $verified = Hash::check($password, $user->fresh()->password) ? 'OK' : 'FAILED';

        $this->info('Super-admin account ready.');
        $this->line('  username: ' . $username);
        $this->line('  password: ' . $password);
        $this->line('  role:     ' . $user->role . ' (role_id=' . $user->role_id . ')');
        $this->line('  password check: ' . $verified);

        return self::SUCCESS;
    }
}
