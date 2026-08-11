<?php
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\ContractCompanyController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FinancialTransactionController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('drivers', DriverController::class)->names('drivers');
    Route::resource('settlements', SettlementController::class)->names('settlements');
    Route::resource('partners', ContractCompanyController::class)->names('partners');
    Route::resource('currencies', CurrencyController::class)->names('currencies');
    Route::resource('payment-methods', PaymentMethodController::class)->names('payment_methods');
    Route::resource('collections', CollectionController::class)->names('collections');
    Route::post('/settlements/pay', [SettlementController::class, 'pay'])
        ->name('settlements.pay');
    Route::post('/settlements/pay-settlement', [SettlementController::class, 'paySettlement'])
        ->name('settlements.pay-settlement');
    Route::resource('transactions', FinancialTransactionController::class)->names('transactions');
    Route::resource('company-info', CompanyInfoController::class)->names('company_info');
    Route::get('/reports/drivers', [ReportController::class, 'driverReports'])->name('reports.drivers');
    Route::get('/reports/drivers/print', [ReportController::class, 'driverReportsPrint'])->name('reports.drivers.print');
    Route::get('/reports/partners', [ReportController::class, 'partnerReports'])->name('reports.partners');
    Route::get('/reports/partners/print', [ReportController::class, 'partnerReportsPrint'])->name('reports.partners.print');
    Route::get('/reports/company', [ReportController::class, 'companyReports'])->name('reports.company');
    Route::get('/reports/company/print', [ReportController::class, 'companyReportsPrint'])->name('reports.company.print');
    Route::get('/reports/transactions/print', [FinancialTransactionController::class, 'print'])
        ->name('reports.transactions.print');
    Route::resource('roles', RoleController::class)->names('roles');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('activity-logs', ActivityLogController::class);
    Route::get('/settlements/{settlement}/print', [SettlementController::class, 'print'])
        ->name('settlements.print');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
    Route::get('/language/{locale}', function ($locale) {
        if (! in_array($locale, ['en', 'ar'])) {
            abort(404);
        }
        Session::put('locale', $locale);
        return back();
    })->name('language');
});
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');;
