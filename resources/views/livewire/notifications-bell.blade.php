<div wire:poll.45s x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="md-icon-btn relative" aria-label="{{ __('messages.notifications') }}">
        <span class="material-symbols-rounded">notifications</span>
        @if($unread > 0)
            <span class="absolute -top-0.5 -end-0.5 min-w-[18px] h-[18px] px-1 bg-error text-on-error rounded-full text-[10px] font-bold flex items-center justify-center border border-surface">{{ $unread > 9 ? '9+' : $unread }}</span>
        @endif
    </button>

    <div x-show="open" x-cloak @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} top-full mt-2 w-80 bg-surface rounded-md-md shadow-md-4 border z-50 overflow-hidden" style="border-color:var(--md-outline-variant)">

        <div class="flex items-center justify-between px-4 py-3 border-b bg-surface-low" style="border-color:var(--md-outline-variant)">
            <p class="text-label text-on-surface">{{ __('messages.notifications') }}</p>
            @if($unread > 0)
                <button wire:click="markAllRead" class="text-label-sm text-primary">{{ __('messages.mark_all_read') }}</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto custom-scrollbar">
            @forelse($items as $n)
                <button wire:click="markRead('{{ $n->id }}')"
                        class="md-state w-full text-start flex items-start gap-3 px-4 py-3 border-b last:border-0 {{ $n->read_at ? '' : 'bg-primary-container/30' }}"
                        style="border-color:var(--md-outline-variant)">
                    <span class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center shrink-0 text-primary">
                        <span class="material-symbols-rounded" style="font-size:20px">{{ $n->data['icon'] ?? 'notifications' }}</span>
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-label text-on-surface">{{ __('messages.' . ($n->data['message_key'] ?? 'notifications')) }}</span>
                        <span class="block text-label-sm text-on-surface-variant">#{{ $n->data['card_number'] ?? '' }} · {{ $n->created_at->diffForHumans() }}</span>
                    </span>
                    @if(!$n->read_at)
                        <span class="w-2 h-2 rounded-full bg-primary mt-1 shrink-0"></span>
                    @endif
                </button>
            @empty
                <div class="px-4 py-10 text-center text-on-surface-variant">
                    <span class="material-symbols-rounded" style="font-size:40px">notifications_off</span>
                    <p class="text-label mt-2">{{ __('messages.no_notifications') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
