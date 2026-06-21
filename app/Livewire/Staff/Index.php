<?php

namespace App\Livewire\Staff;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingStaffId = null;

    // Form fields
    public $name = '';
    public $username = '';
    public $phone = '';
    public $role = 'reception';
    public $password = '';

    protected $queryString = ['search'];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->username = '';
        $this->phone = '';
        $this->role = 'reception';
        $this->password = '';
        $this->editingStaffId = null;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . ($this->editingStaffId ?? 'NULL'),
            'role' => 'required|in:reception,technician,qa,manager',
        ];

        if (!$this->editingStaffId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        if ($this->editingStaffId) {
            $user = User::find($this->editingStaffId);
            $data = [
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'role' => $this->role,
                'role_id' => Role::where('name', $this->role)->first()?->id,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            session()->flash('success', __('messages.staff_updated_success'));
        } else {
            User::create([
                'name' => $this->name,
                'username' => $this->username,
                'phone' => $this->phone,
                'role' => $this->role,
                'role_id' => Role::where('name', $this->role)->first()?->id,
                'password' => Hash::make($this->password),
            ]);
            session()->flash('success', __('messages.staff_added_success'));
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editingStaffId = $id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->showModal = true;
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', __('messages.cannot_delete_self'));
            return;
        }
        User::findOrFail($id)->delete();
        session()->flash('success', __('messages.staff_deleted_success'));
    }

    public function render()
    {
        $staff = User::where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            })
            ->with('role_relation')
            ->paginate(10);

        return view('livewire.staff.index', [
            'staff' => $staff,
            'availableRoles' => Role::all()
        ])->layout('layouts.app');
    }
}
