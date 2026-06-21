<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions
{
    public function role_relation()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    /**
     * Check if user has a specific permission key.
     */
    public function hasPermission(string $permissionKey): bool
    {
        $role = $this->role_relation;
        
        if (!$role) {
            return false;
        }

        // Managers have all permissions by default
        if ($role->name === 'manager') {
            return true;
        }

        return $role->permissions()->where('key', $permissionKey)->exists();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
}
