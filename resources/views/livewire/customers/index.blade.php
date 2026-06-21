<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.customers') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.manage_all_customers') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
                <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-64 rounded-md-md" placeholder="{{ __('messages.search_placeholder') }}">
            </div>
            <button wire:click="openModal" class="md-btn md-btn-filled">
                <span class="material-symbols-rounded" style="font-size:20px">person_add</span>
                {{ __('messages.add_customer') }}
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
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.full_name') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.national_id') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.phone') }}</th>
                        <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.date_added') }}</th>
                        <th class="px-6 py-4 text-end text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr class="border-b last:border-0 hover:bg-surface-low transition-colors" style="border-color:var(--md-outline-variant)">
                            <td class="px-6 py-4 text-label text-on-surface-variant">{{ $customer->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">{{ mb_substr($customer->full_name, 0, 1) }}</div>
                                    <span class="text-label text-on-surface">{{ $customer->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-label text-on-surface-variant">{{ $customer->national_id }}</td>
                            <td class="px-6 py-4"><span class="md-status bg-surface-container text-on-surface-variant">{{ $customer->phone }}</span></td>
                            <td class="px-6 py-4 text-label-sm text-on-surface-variant">{{ $customer->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $customer->id }})" class="md-icon-btn" title="{{ __('messages.edit') }}"><span class="material-symbols-rounded" style="font-size:20px">edit</span></button>
                                    <button wire:click="delete({{ $customer->id }})" wire:confirm="{{ __('messages.confirm_delete_customer') }}" class="md-icon-btn hover:text-error" title="{{ __('messages.delete') }}"><span class="material-symbols-rounded" style="font-size:20px">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-body text-on-surface-variant">{{ __('messages.no_customers_found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t bg-surface-low" style="border-color:var(--md-outline-variant)">{{ $customers->links() }}</div>
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
                            <h2 class="text-title-lg text-on-surface">{{ $editingCustomerId ? __('messages.edit_customer') : __('messages.add_customer') }}</h2>
                            <button wire:click="$set('showModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 py-6">
                            <form wire:submit="save" id="add-customer-form" class="space-y-5">
                                <div>
                                    <label class="md-label">{{ __('messages.full_name') }}</label>
                                    <input wire:model="full_name" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.enter_full_name') }}">
                                    @error('full_name') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="md-label">{{ __('messages.national_id') }}</label>
                                    <input wire:model="national_id" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.enter_national_id') }}">
                                    @error('national_id') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="md-label">{{ __('messages.phone') }}</label>
                                    <input wire:model="phone" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.enter_phone') }}">
                                    @error('phone') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="md-label">{{ __('messages.address') }}</label>
                                    <input wire:model="address" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.enter_address') }}">
                                    @error('address') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="md-label">{{ __('messages.notes') }}</label>
                                    <textarea wire:model="notes" rows="4" class="md-field" placeholder="{{ __('messages.any_notes') }}"></textarea>
                                    @error('notes') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                            </form>
                        </div>

                        <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                            <button type="submit" form="add-customer-form" class="md-btn md-btn-filled flex-1">{{ __('messages.save_customer') }}</button>
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
