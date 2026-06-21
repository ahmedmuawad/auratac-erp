<header class="md-app-bar">
    {{-- Start: menu + search --}}
    <div class="flex items-center gap-3 flex-1">
        <button @click="sidebarOpen = !sidebarOpen" class="md-icon-btn md:hidden" aria-label="القائمة">
            <span class="material-symbols-rounded">menu</span>
        </button>

        <div class="relative max-w-md w-full hidden sm:block">
            <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">search</span>
            <input type="text"
                   class="block w-full ps-11 pe-4 h-10 rounded-md-xl bg-surface-container text-on-surface text-body placeholder:text-on-surface-variant border-none focus:bg-surface-high focus:ring-2 focus:ring-primary/30 transition-all"
                   placeholder="{{ __('messages.search_placeholder') }}">
        </div>
    </div>

    {{-- End: language, notifications, profile --}}
    <div class="flex items-center gap-1 md:gap-2">
        <a href="{{ url('lang/' . (app()->getLocale() == 'ar' ? 'en' : 'ar')) }}"
           class="md-state flex items-center gap-2 h-10 px-3 rounded-md-xl text-label text-on-surface-variant border" style="border-color:var(--md-outline-variant)">
            <span class="material-symbols-rounded" style="font-size:20px">language</span>
            <span class="hidden sm:inline">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
            <span class="sm:hidden uppercase">{{ app()->getLocale() == 'ar' ? 'EN' : 'AR' }}</span>
        </a>

        <button class="md-icon-btn relative" aria-label="الإشعارات">
            <span class="material-symbols-rounded">notifications</span>
            <span class="absolute top-2 end-2 w-2.5 h-2.5 bg-error rounded-full border-2 border-surface"></span>
        </button>

        <div class="w-px h-8 bg-outline-variant mx-1 hidden sm:block"></div>

        <div x-data="{ open: false }" class="relative flex items-center gap-3 ps-1 cursor-pointer" @click="open = !open">
            <div class="text-end hidden lg:block leading-tight">
                <h3 class="text-label text-on-surface">{{ auth()->user()->name }}</h3>
                <p class="text-label-sm text-on-surface-variant uppercase tracking-wide">{{ __('messages.' . auth()->user()->role) }}</p>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=8A6A3D&color=FFFFFF&bold=true"
                 class="w-10 h-10 rounded-full" alt="Avatar">

            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} top-full mt-2 w-52 bg-surface rounded-md-md shadow-md-3 border py-2 z-50" style="border-color:var(--md-outline-variant)">
                <div class="px-4 py-2 border-b mb-1" style="border-color:var(--md-outline-variant)">
                    <p class="text-label-sm text-on-surface-variant uppercase tracking-widest">{{ __('messages.username') }}</p>
                    <p class="text-label text-on-surface">{{ auth()->user()->username }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="md-state w-full flex items-center gap-3 px-4 h-11 text-label text-error">
                        <span class="material-symbols-rounded" style="font-size:20px">logout</span>
                        {{ __('messages.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
