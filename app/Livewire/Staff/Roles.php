<?php

namespace App\Livewire\Staff;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;

class Roles extends Component
{
    public $showModal = false;
    public $editingRoleId = null;
    
    // Form fields
    public $name;
    public $display_name;
    public $description;
    public $selectedPermissions = [];

    public function mount()
    {
        // We only need to ensure basic state
    }

    public function openModal($roleId = null)
    {
        $this->resetErrorBag();
        if ($roleId) {
            $role = Role::find($roleId);
            $this->editingRoleId = $roleId;
            $this->name = $role->name;
            $this->display_name = $role->display_name;
            $this->description = $role->description;
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        } else {
            $this->editingRoleId = null;
            $this->name = '';
            $this->display_name = '';
            $this->description = '';
            $this->selectedPermissions = [];
        }
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->editingRoleId,
            'display_name' => 'required',
        ]);

        $role = Role::updateOrCreate(['id' => $this->editingRoleId], [
            'name' => $this->name,
            'display_name' => $this->display_name,
            'description' => $this->description,
        ]);

        $role->permissions()->sync($this->selectedPermissions);

        $this->showModal = false;
        session()->flash('success', 'تم حفظ الدور بنجاح');
    }

    public function render()
    {
        return view('livewire.staff.roles', [
            'roles' => Role::with('permissions')->get(),
            'permissionGroups' => Permission::all()->groupBy('group')
        ])->layout('layouts.app');
    }
}
