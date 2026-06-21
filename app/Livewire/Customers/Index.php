<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingCustomerId = null;

    // Form fields
    public $full_name = '';
    public $national_id = '';
    public $phone = '';
    public $address = '';
    public $notes = '';

    protected $queryString = ['search'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editingCustomerId = null;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->editingCustomerId = $id;
        $this->full_name = $customer->full_name;
        $this->national_id = $customer->national_id;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->notes = $customer->notes;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();
        session()->flash('success', __('messages.customer_deleted_success'));
    }

    public function resetForm()
    {
        $this->full_name = '';
        $this->national_id = '';
        $this->phone = '';
        $this->address = '';
        $this->notes = '';
        $this->editingCustomerId = null;
    }

    public function save()
    {
        $rules = [
            'full_name' => 'required|string|min:3',
            'national_id' => 'required|string|unique:customers,national_id,' . ($this->editingCustomerId ?? 'NULL'),
            'phone' => 'required|string|unique:customers,phone,' . ($this->editingCustomerId ?? 'NULL'),
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ];

        $this->validate($rules);

        if ($this->editingCustomerId) {
            $customer = Customer::find($this->editingCustomerId);
            $customer->update([
                'full_name' => $this->full_name,
                'national_id' => $this->national_id,
                'phone' => $this->phone,
                'address' => $this->address,
                'notes' => $this->notes,
            ]);
            session()->flash('success', __('messages.customer_updated_success'));
        } else {
            Customer::create([
                'full_name' => $this->full_name,
                'national_id' => $this->national_id,
                'phone' => $this->phone,
                'address' => $this->address,
                'notes' => $this->notes,
            ]);
            session()->flash('success', __('messages.customer_added_success'));
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('customer-saved');
    }

    public function render()
    {
        $customers = Customer::where('full_name', 'like', '%' . $this->search . '%')
            ->orWhere('phone', 'like', '%' . $this->search . '%')
            ->orWhere('national_id', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.customers.index', [
            'customers' => $customers
        ])->layout('layouts.app');
    }
}
