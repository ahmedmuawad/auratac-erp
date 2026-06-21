<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define Permissions
        $permissions = [
            // Customers
            ['key' => 'customers.view', 'group' => 'customers', 'display_name' => 'رؤية العملاء'],
            ['key' => 'customers.create', 'group' => 'customers', 'display_name' => 'إضافة عميل'],
            ['key' => 'customers.edit', 'group' => 'customers', 'display_name' => 'تعديل عميل'],
            ['key' => 'customers.delete', 'group' => 'customers', 'display_name' => 'حذف عميل'],

            // Items
            ['key' => 'items.view', 'group' => 'items', 'display_name' => 'رؤية السجل'],
            ['key' => 'items.create', 'group' => 'items', 'display_name' => 'إضافة قطعة'],
            ['key' => 'items.edit', 'group' => 'items', 'display_name' => 'تعديل قطعة'],
            ['key' => 'items.delete', 'group' => 'items', 'display_name' => 'حذف قطعة'],

            // Maintenance
            ['key' => 'maintenance.view', 'group' => 'maintenance', 'display_name' => 'رؤية الكروت'],
            ['key' => 'maintenance.create', 'group' => 'maintenance', 'display_name' => 'فتح كرت جديد'],
            ['key' => 'maintenance.edit', 'group' => 'maintenance', 'display_name' => 'تعديل الكرت'],
            ['key' => 'maintenance.tech_panel', 'group' => 'maintenance', 'display_name' => 'دخول لوحة الفنيين'],
            ['key' => 'maintenance.qa_delivery', 'group' => 'maintenance', 'display_name' => 'الجودة والتسليم'],

            // Staff
            ['key' => 'staff.manage', 'group' => 'staff', 'display_name' => 'إدارة الموظفين والصلاحيات'],
            ['key' => 'financials.view', 'group' => 'financials', 'display_name' => 'عرض التقارير المالية والديون'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['key' => $p['key']], $p);
        }

        // 2. Define Roles
        $roles = [
            'manager' => 'المدير العام',
            'reception' => 'موظف الاستقبال',
            'technician' => 'فني الصيانة',
            'qa' => 'مشرف الجودة',
        ];

        $roleModels = [];
        foreach ($roles as $name => $displayName) {
            $roleModels[$name] = Role::updateOrCreate(['name' => $name], [
                'display_name' => $displayName
            ]);
        }

        // 3. Assign Permissions to Roles
        
        // Reception
        $roleModels['reception']->permissions()->sync(
            Permission::whereIn('group', ['customers', 'items'])->orWhereIn('key', ['maintenance.view', 'maintenance.create'])->pluck('id')
        );

        // Technician
        $roleModels['technician']->permissions()->sync(
            Permission::whereIn('key', ['maintenance.view', 'maintenance.tech_panel'])->pluck('id')
        );

        // QA
        $roleModels['qa']->permissions()->sync(
            Permission::whereIn('key', ['maintenance.view', 'maintenance.qa_delivery'])->pluck('id')
        );

        // 4. Link Existing Admin to Manager Role
        $admin = User::where('username', 'admin')->first();
        if ($admin) {
            $admin->update(['role_id' => $roleModels['manager']->id]);
        }
    }
}
