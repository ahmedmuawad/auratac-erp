@php
    $user = auth()->user();
    $role = $user->role;
@endphp

<aside
    x-show="true"
    :class="{
        '{{ app()->getLocale() == 'ar' ? 'translate-x-0' : '-translate-x-0' }}': sidebarOpen,
        '{{ app()->getLocale() == 'ar' ? 'translate-x-full' : '-translate-x-full' }}': !sidebarOpen
    }"
    class="w-64 bg-onyx h-screen fixed top-0 {{ app()->getLocale() == 'ar' ? 'right-0' : 'left-0' }} flex flex-col p-4 z-50 transition-transform duration-300 ease-in-out md:translate-x-0">

    {{-- Brand --}}
    <div class="flex items-center gap-3 mb-6 px-2 py-2">
        <div class="w-11 h-11 rounded-md-md bg-white flex items-center justify-center p-1.5 shrink-0">
            <img src="{{ asset(get_setting('logo_path', 'logo.png')) }}" alt="Aura Tac" class="w-full h-full object-contain">
        </div>
        <div class="leading-tight">
            <h2 class="text-title-lg text-on-onyx tracking-wide">{{ get_setting('system_name', 'AURA TAC') }}</h2>
            <p class="text-label-sm text-primary uppercase tracking-[0.2em] mt-0.5">{{ get_setting('system_name_en', 'Maintenance') }}</p>
        </div>
    </div>

    {{-- Primary action (FAB-style) --}}
    <a href="{{ route('maintenance.quick-ticket') }}"
       class="md-state flex items-center gap-3 h-14 px-5 rounded-md-lg bg-primary text-on-primary font-bold mb-6 shadow-md-2">
        <span class="material-symbols-rounded" style="font-size:24px">bolt</span>
        <span class="text-label">{{ __('messages.quick_receive') }}</span>
    </a>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar pe-1 space-y-6">

        {{-- Main --}}
        <div class="space-y-1">
            <p class="px-4 mb-2 text-label-sm text-on-onyx-variant uppercase tracking-widest">{{ __('messages.main_menu') }}</p>

            <a href="{{ route('dashboard') }}" class="md-nav-item {{ request()->routeIs('dashboard') ? 'md-nav-item-active' : '' }}">
                <span class="material-symbols-rounded" style="font-size:22px">space_dashboard</span>
                <span>{{ __('messages.dashboard') }}</span>
            </a>

            @if(auth()->user()->hasPermission('customers.view'))
                <a href="{{ route('customers.index') }}" class="md-nav-item {{ request()->routeIs('customers.*') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">groups</span>
                    <span>{{ __('messages.customers_management') }}</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('items.view'))
                <a href="{{ route('items.index') }}" class="md-nav-item {{ request()->routeIs('items.*') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">inventory_2</span>
                    <span>{{ __('messages.items_directory') }}</span>
                </a>
            @endif
        </div>

        {{-- Maintenance Department --}}
        <div class="space-y-1 pt-4 border-t border-white/5">
            <p class="px-4 mb-2 text-label-sm text-on-onyx-variant uppercase tracking-widest">{{ __('messages.maintenance_department') }}</p>

            @if(auth()->user()->hasPermission('maintenance.view'))
                <a href="{{ route('maintenance.index') }}" class="md-nav-item {{ request()->routeIs('maintenance.index') && !request('filter_status') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">assignment</span>
                    <span>{{ __('messages.maintenance_cards') }}</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('maintenance.tech_panel'))
                <a href="{{ route('maintenance.technician') }}" class="md-nav-item {{ request()->routeIs('maintenance.technician') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">construction</span>
                    <span>{{ __('messages.technician_panel') }}</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('maintenance.qa_delivery'))
                <a href="{{ route('maintenance.qa') }}" class="md-nav-item {{ request()->routeIs('maintenance.qa') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">verified_user</span>
                    <span>{{ __('messages.quality_check') }}</span>
                </a>
                <a href="{{ route('maintenance.delivery') }}" class="md-nav-item {{ request()->routeIs('maintenance.delivery') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">local_shipping</span>
                    <span>{{ __('messages.delivery') }}</span>
                </a>
            @endif

            @if(auth()->user()->hasPermission('maintenance.view'))
                <a href="{{ route('maintenance.index', ['filter_status' => 'delivered']) }}" class="md-nav-item {{ request('filter_status') == 'delivered' ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">inventory</span>
                    <span>{{ __('messages.cards_archive') }}</span>
                </a>
            @endif
        </div>

        {{-- Reports --}}
        <div class="space-y-1 pt-4 border-t border-white/5">
            <p class="px-4 mb-2 text-label-sm text-on-onyx-variant uppercase tracking-widest">{{ __('messages.reports_inquiry') }}</p>

            <a href="{{ route('reports.history') }}" class="md-nav-item {{ request()->routeIs('reports.history') ? 'md-nav-item-active' : '' }}">
                <span class="material-symbols-rounded" style="font-size:22px">manage_search</span>
                <span>{{ __('messages.comprehensive_search') }}</span>
            </a>

            <a href="{{ route('reports.analytics') }}" class="md-nav-item {{ request()->routeIs('reports.analytics') ? 'md-nav-item-active' : '' }}">
                <span class="material-symbols-rounded" style="font-size:22px">analytics</span>
                <span>{{ __('messages.analytics_reports') }}</span>
            </a>
        </div>

        {{-- Admin --}}
        @if($role == 'manager')
            <div class="space-y-1 pt-4 border-t border-white/5">
                <p class="px-4 mb-2 text-label-sm text-on-onyx-variant uppercase tracking-widest">{{ __('messages.system_management') }}</p>

                @if(auth()->user()->hasPermission('staff.manage'))
                    <a href="{{ route('staff.index') }}" class="md-nav-item {{ request()->routeIs('staff.index') ? 'md-nav-item-active' : '' }}">
                        <span class="material-symbols-rounded" style="font-size:22px">badge</span>
                        <span>{{ __('messages.staff_management') }}</span>
                    </a>
                    <a href="{{ route('staff.roles') }}" class="md-nav-item {{ request()->routeIs('staff.roles') ? 'md-nav-item-active' : '' }}">
                        <span class="material-symbols-rounded" style="font-size:22px">admin_panel_settings</span>
                        <span>{{ __('messages.roles_management') }}</span>
                    </a>
                @endif

                @if(auth()->user()->hasPermission('financials.view'))
                    <a href="{{ route('financials.index') }}" class="md-nav-item {{ request()->routeIs('financials.*') ? 'md-nav-item-active' : '' }}">
                        <span class="material-symbols-rounded" style="font-size:22px">payments</span>
                        <span>التقارير المالية</span>
                    </a>
                @endif

                <a href="{{ route('settings.index') }}" class="md-nav-item {{ request()->routeIs('settings.*') ? 'md-nav-item-active' : '' }}">
                    <span class="material-symbols-rounded" style="font-size:22px">settings</span>
                    <span>{{ __('messages.settings') }}</span>
                </a>
            </div>
        @endif
    </nav>

    {{-- User --}}
    <div class="mt-4 border-t border-white/5 pt-4">
        <div class="flex items-center gap-3 mb-3 px-2">
            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center font-bold text-on-primary">
                {{ mb_substr($user->name, 0, 1) }}
            </div>
            <div class="leading-tight min-w-0">
                <p class="text-label text-on-onyx truncate">{{ $user->name }}</p>
                <p class="text-label-sm text-primary uppercase tracking-widest">{{ __('messages.'.$user->role) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="md-state w-full flex items-center justify-center gap-2 h-11 rounded-md-xl text-label text-on-onyx-variant hover:text-error">
                <span class="material-symbols-rounded" style="font-size:20px">logout</span>
                {{ __('messages.logout') }}
            </button>
        </form>
    </div>
</aside>
