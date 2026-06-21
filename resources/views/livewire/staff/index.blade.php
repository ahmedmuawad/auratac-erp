<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.staff_management') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.manage_system_users_roles') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
                <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-64 rounded-md-md" placeholder="{{ __('messages.search_staff') }}">
            </div>
            <button wire:click="openModal" class="md-btn md-btn-filled">
                <span class="material-symbols-rounded" style="font-size:20px">person_add</span>
                {{ __('messages.add_staff_member') }}
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="md-card-elevated overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-low border-b" style="border-color:var(--md-outline-variant)">
                    <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.name') }}</th>
                    <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.username') }}</th>
                    <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.role') }}</th>
                    <th class="px-6 py-4 text-start text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.phone') }}</th>
                    <th class="px-6 py-4 text-end text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $user)
                    <tr class="border-b last:border-0 hover:bg-surface-low transition-colors" style="border-color:var(--md-outline-variant)">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold">{{ mb_substr($user->name, 0, 1) }}</div>
                                <span class="text-label text-on-surface">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-label text-on-surface-variant">{{ $user->username }}</td>
                        <td class="px-6 py-4">
                            <span class="md-status {{ $user->role == 'manager' ? 'bg-onyx text-primary' : 'bg-surface-container text-on-surface-variant' }}">{{ $user->role_relation?->display_name ?? __('messages.' . $user->role) }}</span>
                        </td>
                        <td class="px-6 py-4 text-label text-on-surface-variant">{{ $user->phone }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="edit({{ $user->id }})" class="md-icon-btn"><span class="material-symbols-rounded" style="font-size:20px">edit</span></button>
                                <button wire:click="delete({{ $user->id }})" wire:confirm="{{ __('messages.confirm_delete_staff') }}" class="md-icon-btn hover:text-error"><span class="material-symbols-rounded" style="font-size:20px">delete</span></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t bg-surface-low" style="border-color:var(--md-outline-variant)">{{ $staff->links() }}</div>
    </div>

    {{-- Drawer --}}
    @if($showModal)
        <div class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/50 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} max-w-full flex">
                <div class="w-screen max-w-md">
                    <div class="h-full flex flex-col bg-surface shadow-md-4">
                        <div class="p-6 border-b flex items-center justify-between bg-surface-low" style="border-color:var(--md-outline-variant)">
                            <h2 class="text-title-lg text-on-surface">{{ $editingStaffId ? __('messages.edit_staff_member') : __('messages.add_staff_member') }}</h2>
                            <button wire:click="$set('showModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                        </div>

                        <div class="flex-1 overflow-y-auto px-6 py-6 space-y-5">
                            <div>
                                <label class="md-label">{{ __('messages.name') }}</label>
                                <input wire:model="name" type="text" class="md-field rounded-md-sm">
                                @error('name') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="md-label">{{ __('messages.username') }}</label>
                                <input wire:model="username" type="text" class="md-field rounded-md-sm">
                                @error('username') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="md-label">{{ __('messages.phone') }}</label>
                                <input wire:model="phone" type="text" class="md-field rounded-md-sm">
                            </div>
                            <div>
                                <label class="md-label">{{ __('messages.role') }}</label>
                                <select wire:model="role" class="md-field rounded-md-sm">
                                    <option value="">{{ __('messages.select_role') }}</option>
                                    @foreach($availableRoles as $roleOption)
                                        <option value="{{ $roleOption->name }}">{{ $roleOption->display_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="md-label">{{ __('messages.password') }}</label>
                                <input wire:model="password" type="password" class="md-field rounded-md-sm" placeholder="{{ $editingStaffId ? __('messages.leave_blank_to_keep') : '' }}">
                                @error('password') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                            <button wire:click="save" class="md-btn md-btn-filled flex-1">{{ __('messages.save_staff_member') }}</button>
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
