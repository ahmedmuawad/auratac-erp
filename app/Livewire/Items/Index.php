<?php

namespace App\Livewire\Items;

use App\Models\Item;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $editingItemId = null;

    // Form fields
    public $customer_id = '';
    public $item_number = ''; // Serial Number
    public $type = '';
    public $manufacturer = '';
    public $license_number = '';
    public $specs = '';

    // Search for customer
    public $customerSearch = '';
    public $customersList = [];

    protected $queryString = ['search'];

    public function updatedCustomerSearch()
    {
        if (strlen($this->customerSearch) >= 2) {
            $this->customersList = Customer::where('full_name', 'like', '%' . $this->customerSearch . '%')
                ->orWhere('phone', 'like', '%' . $this->customerSearch . '%')
                ->take(5)
                ->get();
        } else {
            $this->customersList = [];
        }
    }

    public function selectCustomer($id, $name)
    {
        $this->customer_id = $id;
        $this->customerSearch = $name;
        $this->customersList = [];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $item = Item::with('customer')->findOrFail($id);
        $this->editingItemId = $id;
        $this->customer_id = $item->customer_id;
        $this->customerSearch = $item->customer->full_name;
        $this->item_number = $item->item_number;
        $this->type = $item->type;
        $this->manufacturer = $item->manufacturer;
        $this->license_number = $item->license_number;
        $this->specs = $item->specs;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Item::findOrFail($id)->delete();
        session()->flash('success', __('messages.item_deleted_success'));
    }

    public function resetForm()
    {
        $this->customer_id = '';
        $this->customerSearch = '';
        $this->item_number = '';
        $this->type = '';
        $this->manufacturer = '';
        $this->license_number = '';
        $this->specs = '';
        $this->editingItemId = null;
        $this->customersList = [];
    }

    public function save()
    {
        $rules = [
            'customer_id' => 'required',
            'item_number' => 'required|string|unique:items,item_number,' . ($this->editingItemId ?? 'NULL'),
            'type' => 'required|string',
            'manufacturer' => 'required|string',
            'license_number' => 'nullable|string',
            'specs' => 'nullable|string',
        ];

        $this->validate($rules);

        if ($this->editingItemId) {
            $item = Item::find($this->editingItemId);
            $item->update([
                'customer_id' => $this->customer_id,
                'item_number' => $this->item_number,
                'type' => $this->type,
                'manufacturer' => $this->manufacturer,
                'license_number' => $this->license_number,
                'specs' => $this->specs,
            ]);
            session()->flash('success', __('messages.item_updated_success'));
        } else {
            Item::create([
                'customer_id' => $this->customer_id,
                'item_number' => $this->item_number,
                'type' => $this->type,
                'manufacturer' => $this->manufacturer,
                'license_number' => $this->license_number,
                'specs' => $this->specs,
            ]);
            session()->flash('success', __('messages.item_added_success'));
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('item-saved');
    }

    public function render()
    {
        $items = Item::with('customer')
            ->where(function($query) {
                $query->where('item_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('customer', function($q) {
                          $q->where('full_name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.items.index', [
            'items' => $items
        ])->layout('layouts.app');
    }
}
