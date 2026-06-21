<header class="md-app-bar"
    x-data="{
        scanOpen: false,
        qr: null,
        startScan() {
            this.scanOpen = true;
            this.$nextTick(() => {
                if (typeof Html5Qrcode === 'undefined') return;
                this.qr = new Html5Qrcode('qr-reader');
                this.qr.start({ facingMode: 'environment' }, { fps: 10, qrbox: { width: 260, height: 160 } },
                    (decoded) => { this.openCard(decoded); },
                    () => {}
                ).catch(() => {});
            });
        },
        stopScan() {
            if (this.qr) { this.qr.stop().then(() => this.qr.clear()).catch(() => {}); this.qr = null; }
            this.scanOpen = false;
        },
        openCard(code) {
            this.stopScan();
            window.location.href = '{{ route('maintenance.index') }}?search=' + encodeURIComponent(code.trim());
        }
    }">

    {{-- Start: menu + scan search --}}
    <div class="flex items-center gap-2 flex-1">
        <button @click="sidebarOpen = !sidebarOpen" class="md-icon-btn md:hidden" aria-label="القائمة">
            <span class="material-symbols-rounded">menu</span>
        </button>

        {{-- Quick scan box: a USB/Bluetooth scanner types the card number + Enter --}}
        <form method="GET" action="{{ route('maintenance.index') }}" class="relative max-w-md w-full hidden sm:block">
            <span class="material-symbols-rounded absolute inset-y-0 start-3 flex items-center text-on-surface-variant pointer-events-none" style="font-size:20px">qr_code_scanner</span>
            <input type="text" name="search" autocomplete="off"
                   class="block w-full ps-11 pe-4 h-10 rounded-md-xl bg-surface-container text-on-surface text-body placeholder:text-on-surface-variant border-none focus:bg-surface-high focus:ring-2 focus:ring-primary/30 transition-all"
                   placeholder="{{ __('messages.scan_or_search') }}">
        </form>

        {{-- Camera scan (mobile) --}}
        <button type="button" @click="startScan()" class="md-icon-btn" aria-label="{{ __('messages.scan_camera') }}" title="{{ __('messages.scan_camera') }}">
            <span class="material-symbols-rounded">photo_camera</span>
        </button>
    </div>

    {{-- End: language, notifications, profile --}}
    <div class="flex items-center gap-1 md:gap-2">
        <a href="{{ url('lang/' . (app()->getLocale() == 'ar' ? 'en' : 'ar')) }}"
           class="md-state flex items-center gap-2 h-10 px-3 rounded-md-xl text-label text-on-surface-variant border" style="border-color:var(--md-outline-variant)">
            <span class="material-symbols-rounded" style="font-size:20px">language</span>
            <span class="hidden sm:inline">{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}</span>
            <span class="sm:hidden uppercase">{{ app()->getLocale() == 'ar' ? 'EN' : 'AR' }}</span>
        </a>

        @livewire('notifications-bell')

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

    {{-- Camera scan modal --}}
    <div x-show="scanOpen" x-cloak class="fixed inset-0 z-[80] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-onyx/70 backdrop-blur-sm" @click="stopScan()"></div>
        <div class="relative bg-surface rounded-md-xl shadow-md-4 w-full max-w-md overflow-hidden">
            <div class="p-5 border-b flex items-center justify-between" style="border-color:var(--md-outline-variant)">
                <h3 class="text-title text-on-surface flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary">qr_code_scanner</span>
                    {{ __('messages.scan_camera') }}
                </h3>
                <button type="button" @click="stopScan()" class="md-icon-btn"><span class="material-symbols-rounded">close</span></button>
            </div>
            <div class="p-5">
                <div id="qr-reader" class="w-full rounded-md-md overflow-hidden"></div>
                <p class="text-label-sm text-on-surface-variant text-center mt-3">{{ __('messages.point_camera_barcode') }}</p>
            </div>
        </div>
    </div>
</header>
