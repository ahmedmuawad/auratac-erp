<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Dashboard;
use App\Http\Controllers\MaintenanceCardController;
use App\Livewire\Auth\Login;

use App\Livewire\Customers\Index as CustomersIndex;
use App\Livewire\Items\Index as ItemsIndex;
use App\Livewire\Maintenance\Index as MaintenanceIndex;
use App\Livewire\Maintenance\TechnicianPanel;
use App\Livewire\Maintenance\Delivery as DeliveryIndex;
use App\Livewire\Maintenance\QualityControl;
use App\Livewire\Financials\Index as FinancialsIndex;
use App\Livewire\Reports\CustomerHistory;
use App\Livewire\Reports\Analytics as AnalyticsIndex;
use App\Livewire\Maintenance\QuickTicket;
use App\Livewire\Settings\Index as SettingsIndex;
use App\Livewire\Staff\Index as StaffIndex;
use App\Livewire\Staff\Roles as RolesIndex;

Route::get('/', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('customers', CustomersIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('customers.index');

Route::get('items', ItemsIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('items.index');

Route::get('maintenance', MaintenanceIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('maintenance.index');

Route::get('maintenance/technician', TechnicianPanel::class)
    ->middleware(['auth', 'verified'])
    ->name('maintenance.technician');

Route::get('maintenance/quality', QualityControl::class)
    ->middleware(['auth', 'verified'])
    ->name('maintenance.qa');

Route::get('maintenance/delivery', DeliveryIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('maintenance.delivery');

Route::get('staff', StaffIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('staff.index');

Route::get('staff/roles', RolesIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('staff.roles');

Route::get('financials', FinancialsIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('financials.index');

Route::get('reports/history', CustomerHistory::class)
    ->middleware(['auth', 'verified'])
    ->name('reports.history');

Route::get('reports/analytics', AnalyticsIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('reports.analytics');

Route::get('maintenance/quick-ticket', QuickTicket::class)
    ->middleware(['auth', 'verified'])
    ->name('maintenance.quick-ticket');

Route::get('settings', SettingsIndex::class)
    ->middleware(['auth', 'verified'])
    ->name('settings.index');

Route::get('maintenance/print/{id}', [MaintenanceCardController::class, 'print'])
    ->middleware(['auth', 'verified'])
    ->name('maintenance.print');

Route::get('maintenance/print-repair/{id}', [MaintenanceCardController::class, 'printRepair'])
    ->middleware(['auth', 'verified'])
    ->name('maintenance.print-repair');

Route::get('maintenance/print-label/{id}', [MaintenanceCardController::class, 'printLabel'])
    ->middleware(['auth', 'verified'])
    ->name('maintenance.print-label');

Route::get('login', Login::class)->name('login')->middleware('guest');

Route::post('logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
});
