<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md-card-elevated p-6">
        <div>
            <h1 class="text-headline text-on-surface flex items-center gap-2">
                <span class="material-symbols-rounded text-primary" style="font-size:28px">construction</span>
                {{ __('messages.technician_command_center') }}
            </h1>
            <p class="text-body text-on-surface-variant mt-1">{{ __('messages.track_active_repairs') }}</p>
        </div>
        <div class="relative">
            <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
            <input wire:model.live="search" type="text" class="md-field !h-11 ps-11 w-full md:w-72 rounded-md-md" placeholder="{{ __('messages.search_by_card_or_customer') }}">
        </div>
    </div>

    {{-- Active repairs --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @forelse($cards as $card)
            @php $meta = $card->statusMeta(); @endphp
            <div class="md-card-elevated p-6 flex flex-col">
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <span class="md-status bg-onyx text-primary">#{{ $card->card_number }}</span>
                        <h3 class="text-title-lg text-on-surface mt-2">{{ $card->customer->full_name }}</h3>
                        <p class="text-label text-on-surface-variant mt-0.5">{{ $card->item->manufacturer }} — {{ $card->item->type }}</p>
                    </div>
                    <span class="md-status bg-{{ $meta['role'] === 'primary' ? 'primary-container text-on-primary-container' : ($meta['role'] === 'warning' ? 'warning-container text-on-warning-container' : 'surface-container text-on-surface-variant') }}">{{ $meta['label'] }}</span>
                </div>

                {{-- Reception requests --}}
                <div class="md-card-filled p-4 mb-4">
                    <h4 class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-2 flex items-center gap-2">
                        <span class="material-symbols-rounded" style="font-size:18px">assignment</span>
                        {{ __('messages.reception_requests') }}
                    </h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($card->repair_requests as $req)
                            @if(trim($req))
                                <span class="md-chip">{{ $req }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Repair log --}}
                @if($card->repairTasks->count() > 0)
                    <div class="mb-5">
                        <h4 class="text-label-sm text-on-surface-variant uppercase tracking-widest mb-2 flex items-center gap-2">
                            <span class="material-symbols-rounded" style="font-size:18px">history</span>
                            {{ __('messages.repair_log') }}
                        </h4>
                        <div class="space-y-2 max-h-40 overflow-y-auto custom-scrollbar pe-1">
                            @foreach($card->repairTasks as $task)
                                <div class="p-3 md-card">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-label-sm text-primary">{{ $task->technician->name }}</span>
                                        <span class="text-label-sm text-on-surface-variant">{{ optional($task->start_time)->format('m/d H:i') }}</span>
                                    </div>
                                    <p class="text-label text-on-surface">{{ $task->task_description }}</p>
                                    @if($task->used_parts_text)
                                        <p class="text-label-sm text-tertiary mt-1">قطع غيار: {{ $task->used_parts_text }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex items-center gap-2 mt-auto">
                    <button wire:click="openTaskModal({{ $card->id }})" class="md-btn md-btn-filled flex-1">
                        <span class="material-symbols-rounded" style="font-size:20px">add</span>
                        {{ __('messages.add_repair_session') }}
                    </button>
                    @if($card->status != 'waiting_parts')
                        <button wire:click="updateStatus({{ $card->id }}, 'waiting_parts')" class="md-icon-btn bg-warning-container" style="color:var(--md-on-warning-container)" title="{{ __('messages.mark_waiting_parts') }}">
                            <span class="material-symbols-rounded" style="font-size:20px">pause</span>
                        </button>
                    @endif
                    <button wire:click="updateStatus({{ $card->id }}, 'ready_for_qa')" class="md-icon-btn bg-success-container" style="color:var(--md-on-success-container)" title="{{ __('messages.send_to_qa') }}">
                        <span class="material-symbols-rounded" style="font-size:20px">verified_user</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="lg:col-span-2 py-20 md-card flex flex-col items-center justify-center text-on-surface-variant">
                <span class="material-symbols-rounded mb-3" style="font-size:56px">task_alt</span>
                <p class="text-label uppercase tracking-widest">{{ __('messages.no_active_tasks') }}</p>
            </div>
        @endforelse
    </div>

    @if($cards->hasPages())
        <div>{{ $cards->links() }}</div>
    @endif

    {{-- Task modal --}}
    @if($showTaskModal && $selectedCard)
        <div class="fixed inset-0 z-[60]" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-onyx/60 backdrop-blur-sm" wire:click="$set('showTaskModal', false)"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-surface w-full max-w-lg rounded-md-xl shadow-md-4 overflow-hidden">
                    <div class="p-6 border-b flex items-center justify-between" style="border-color:var(--md-outline-variant)">
                        <div>
                            <h2 class="text-title-lg text-on-surface">{{ __('messages.log_repair_session') }}</h2>
                            <p class="text-label-sm text-on-surface-variant uppercase tracking-widest mt-1">#{{ $selectedCard->card_number }}</p>
                        </div>
                        <button wire:click="$set('showTaskModal', false)" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
                    </div>

                    <div class="p-6 space-y-5">
                        <div>
                            <label class="md-label">{{ __('messages.what_did_you_do') }}</label>
                            <textarea wire:model="task_description" rows="3" class="md-field" placeholder="{{ __('messages.describe_repair_placeholder') }}"></textarea>
                            @error('task_description') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="md-label">{{ __('messages.spare_parts_used') }}</label>
                            <input wire:model="used_parts_text" type="text" class="md-field rounded-md-sm" placeholder="{{ __('messages.list_parts_placeholder') }}">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="md-label">{{ __('messages.start_time') }}</label>
                                <input wire:model="start_time" type="datetime-local" class="md-field rounded-md-sm">
                                @error('start_time') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="md-label">{{ __('messages.end_time') }}</label>
                                <input wire:model="end_time" type="datetime-local" class="md-field rounded-md-sm">
                                @error('end_time') <span class="text-label-sm text-error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="p-6 border-t bg-surface-low flex gap-3" style="border-color:var(--md-outline-variant)">
                        <button wire:click="saveTask" class="md-btn md-btn-filled flex-1">{{ __('messages.save_repair_session') }}</button>
                        <button wire:click="$set('showTaskModal', false)" class="md-btn md-btn-outlined">{{ __('messages.cancel') }}</button>
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
