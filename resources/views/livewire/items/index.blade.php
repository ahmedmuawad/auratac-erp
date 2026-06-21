<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.items_directory') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.manage_all_items') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
                <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-64 rounded-md-md" placeholder="{{ __('messages.search_by_serial_or_customer') }}">
            </div>
            <button wire:click="openModal" class="md-btn md-btn-filled">
                <span class="material-symbols-rounded" style="font-size:20px">add</span>
                {{ __('messages.add_item') }}
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="md-card-elevated overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-surface-low border-b" style="border-color:var(--md-outline-variant)">
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">#</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.item_serial') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.item_type') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.manufacturer') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.owner') }}</th>
                        <th class="px-6 py-4 text-end text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="border-b last:border-0 hover:bg-surface-low transition-colors" style="border-color:var(--md-outline-variant)">
                            <td class="px-6 py-4 text-label text-on-surface-variant">{{ $item->id }}</td>
                            <td class="px-6 py-4"><span class="md-status bg-onyx text-primary">{{ $item->item_number }}</span></td>
                            <td class="px-6 py-4 text-label text-on-surface">{{ $item->type }}</td>
                            <td class="px-6 py-4 text-label text-on-surface-variant">{{ $item->manufacturer }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-tertiary-container text-on-tertiary-container flex items-center justify-center font-bold text-label-sm">{{ mb_substr($item->customer->full_name, 0, 1) }}</div>
                                    <span class="text-label text-on-surface-variant">{{ $item->customer->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $item->id }})" class="md-icon-btn"><span class="material-symbols-rounded" style="font-size:20px">edit</span></button>
                                    <button wire:click="delete({{ $item->id }})" wire:confirm="{{ __('messages.confirm_delete_item') }}" class="md-icon-btn hover:text-error"><span class="material-symbols-rounded" style="font-size:20px">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-body text-on-surface-variant">{{ __('messages.no_items_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="px-6 py-4 border-t bg-surface-low" style="border-color:var(--md-outline-variant)">{{ $items->links() }}</div>
        @endif
    </div>

    {{-- Drawer --}}
    @if($showModal)
        <div class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/50 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} max-w-full flex">
                <div class="w-screen max-w-md">
                    <div class="h-full flex flex-col bg-surface shadow-md-4">
                        <div class="p-6 border-b flex items-center justify-between bg-surface-low" style="border-color:var(--md-outline-variant)">
                            <h2 class="text-title-lg text-on-surface">{{ $editingItemId ? __('messages.edit_item') : __('messages.add_item') }}</h2>
                            <button wire:click="$set('showModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 py-6">
                            <form wire:submit="save" id="add-item-form" class="space-y-5">
                                <div class="relative">
                                    <label class="md-label">{{ __('messages.owner') }}</label>
                                    <div class="relative">
                                        <input wire:model.live="customerSearch" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.search_customer_placeholder') }}" @if($customer_id) disabled @endif>
                                        @if($customer_id)
                                            <button type="button" wire:click="$set('customer_id', ''); $set('customerSearch', '')" class="absolute inset-y-0 end-0 pe-4 flex items-center text-error"><span class="material-symbols-rounded" style="font-size:20px">close</span></button>
                                        @endif
                                    </div>
                                    @if(count($customersList) > 0)
                                        <div class="absolute z-20 w-full mt-1 bg-surface border rounded-md-md shadow-md-3 overflow-hidden" style="border-color:var(--md-outline-variant)">
                                            @foreach($customersList as $cust)
                                                <button type="button" wire:click="selectCustomer({{ $cust->id }}, '{{ $cust->full_name }}')" class="md-state w-full text-start px-4 py-3 flex items-center justify-between">
                                                    <div><p class="text-label text-on-surface">{{ $cust->full_name }}</p><p class="text-label-sm text-on-surface-variant">{{ $cust->phone }}</p></div>
                                                    <span class="material-symbols-rounded text-primary" style="font-size:20px">check</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @error('customer_id') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="md-label">{{ __('messages.item_serial') }}</label>
                                        <input wire:model="item_number" type="text" class="md-field rounded-md-sm" placeholder="SN-0000">
                                        @error('item_number') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="md-label">{{ __('messages.license_number') }}</label>
                                        <input wire:model="license_number" type="text" class="md-field rounded-md-sm" placeholder="LIC-000">
                                    </div>
                                </div>

                                <div>
                                    <label class="md-label">{{ __('messages.item_type') }}</label>
                                    <input wire:model="type" list="item-types" class="md-field rounded-md-sm" placeholder="{{ __('messages.example_pistol') }}">
                                    <datalist id="item-types">
                                        <option value="مسدس"><option value="بندقية"><option value="شوزن"><option value="رشاش">
                                    </datalist>
                                    @error('type') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="md-label">{{ __('messages.manufacturer') }}</label>
                                    <input wire:model="manufacturer" type="text" class="md-field rounded-md-sm" placeholder="Glock, Beretta, ...">
                                    @error('manufacturer') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="md-label">{{ __('messages.specs') }}</label>
                                    <textarea wire:model="specs" rows="3" class="md-field" placeholder="{{ __('messages.any_specs') }}"></textarea>
                                </div>
                            </form>
                        </div>

                        <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                            <button type="submit" form="add-item-form" class="md-btn md-btn-filled flex-1">{{ __('messages.save_item') }}</button>
                            <button wire:click="$set('showModal', false)" class="md-btn md-btn-outlined">{{ __('messages.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast --}}
    @if(session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-8 end-8 z-[100]">
            <div class="bg-onyx text-on-onyx px-5 py-4 rounded-md-md shadow-md-4 flex items-center gap-3">
                <span class="material-symbols-rounded text-success" style="font-size:22px">check_circle</span>
                <p class="text-label">{{ session('success') }}</p>
            </div>
        </div>
    @endif
</div>
