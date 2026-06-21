<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface">{{ __('messages.roles_perms_mgmt') }}</h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.roles_perms_sub') }}</p>
        </div>
        <button wire:click="openModal" class="md-btn md-btn-filled">
            <span class="material-symbols-rounded" style="font-size:20px">add</span>
            {{ __('messages.add_role') }}
        </button>
    </div>

    {{-- Roles grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($roles as $role)
            <div class="md-card-elevated p-6 flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <span class="md-status bg-surface-container text-on-surface-variant">{{ $role->name }}</span>
                    <span class="md-status bg-success-container text-on-success-container">{{ __('messages.active_role') }}</span>
                </div>
                <h3 class="text-title-lg text-on-surface mb-1">{{ $role->display_name }}</h3>
                <p class="text-label text-on-surface-variant mb-5">{{ $role->description ?? '—' }}</p>

                <div class="mb-5">
                    <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-2">{{ __('messages.granted_perms') }} ({{ $role->permissions->count() }})</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($role->permissions->take(5) as $perm)
                            <span class="md-chip">{{ $perm->display_name }}</span>
                        @empty
                            <span class="text-label text-on-surface-variant">{{ __('messages.no_perms_set') }}</span>
                        @endforelse
                        @if($role->permissions->count() > 5)
                            <span class="md-chip md-chip-selected">+ {{ $role->permissions->count() - 5 }} {{ __('messages.others_extra') }}</span>
                        @endif
                    </div>
                </div>

                <button wire:click="openModal({{ $role->id }})" class="md-btn md-btn-tonal w-full mt-auto">{{ __('messages.edit_role_perms') }}</button>
            </div>
        @endforeach
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-[70]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/60 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            <div class="fixed inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} max-w-full flex">
                <div class="w-screen max-w-2xl">
                    <div class="h-full flex flex-col bg-surface shadow-md-4">
                        <div class="p-6 border-b flex items-center justify-between bg-surface-low" style="border-color:var(--md-outline-variant)">
                            <div>
                                <h2 class="text-title-lg text-on-surface">{{ $editingRoleId ? __('messages.edit_role') : __('messages.add_role') }}</h2>
                                <p class="text-label-sm text-on-surface-variant mt-1">{{ __('messages.define_role_perms') }}</p>
                            </div>
                            <button wire:click="$set('showModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="md-label">{{ __('messages.role_name_code') }}</label>
                                    <input wire:model="name" type="text" placeholder="e.g. supervisor" class="md-field rounded-md-sm" dir="ltr">
                                    @error('name') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="md-label">{{ __('messages.role_name_display') }}</label>
                                    <input wire:model="display_name" type="text" class="md-field rounded-md-sm">
                                    @error('display_name') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="md-label">{{ __('messages.role_description') }}</label>
                                <textarea wire:model="description" rows="2" class="md-field"></textarea>
                            </div>

                            <div class="space-y-4">
                                <p class="text-label-sm text-primary uppercase tracking-widest border-b pb-2" style="border-color:var(--md-outline-variant)">{{ __('messages.perms_detail') }}</p>
                                @foreach($permissionGroups as $group => $perms)
                                    <div class="md-card-filled p-5 space-y-3">
                                        <h4 class="text-label text-on-surface flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                            {{ __('messages.' . $group) ?? $group }}
                                        </h4>
                                        <div class="grid grid-cols-2 gap-3">
                                            @foreach($perms as $permission)
                                                <label class="flex items-center gap-3 cursor-pointer">
                                                    <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="w-5 h-5 accent-[#8A6A3D]">
                                                    <span class="text-label text-on-surface-variant">{{ $permission->display_name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                            <button wire:click="$set('showModal', false)" class="md-btn md-btn-outlined flex-1">{{ __('messages.cancel') }}</button>
                            <button wire:click="save" class="md-btn md-btn-filled flex-[2]">{{ __('messages.save_role_perms') }}</button>
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
