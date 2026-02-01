<?php

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\LoginAttemptController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginHeroController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Property\RentSummaryController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyExpensesController;
use App\Http\Controllers\PropertyInvestmentController;
use App\Http\Controllers\PropertyLeaseAgreementController;
use App\Http\Controllers\PropertyLeaseTemplateController;
use App\Http\Controllers\PropertyPaymentsController;
use App\Http\Controllers\PropertyReportController;
use App\Http\Controllers\PropertyUnitController;
use App\Http\Controllers\PropertyUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WirepickCallbackController;
use App\Http\Controllers\WirepickPaymentController;
use Illuminate\Support\Facades\Route;






















/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/





Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth','approved')->group(function () {


//Properties
    Route::resource('properties', PropertyController::class);
        Route::get('/property_create', [PropertyController::class, 'propertyCreate'])->name('properties.create_form');
Route::post('/properties/sync', [PropertyController::class, 'syncFromQBO'])
    ->name('properties.sync')
    ->middleware('auth');


    Route::put('/users/{user}/kyc', [PropertyUserController::class, 'updateKyc'])
     ->name('users.kyc.update');
 Route::patch('/users/{user:slug}/kyc', [PropertyUserController::class, 'updateKyc'])->name('users.updateKyc');


Route::post(
    '/properties/{property:slug}/users/{user:slug}/assign-lease',
    [PropertyLeaseAgreementController::class, 'assignAndGenerateLink']
)->name('property.lease.assign');

Route::get(
  '/properties/{property:slug}/lease-assign',
  [PropertyLeaseAgreementController::class, 'assignBoard']
)->name('property.lease.assign.board');

Route::post(
    '/properties/{property:slug}/lease-assign',
    [PropertyLeaseAgreementController::class, 'assignFromBoard']
)->name('property.lease.assign.api');


Route::prefix('properties/{property}')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/rent-summary', [
            \App\Http\Controllers\Property\RentSummaryController::class,
            'index'
        ])->name('property.rent.summary');

    });



// Landlord dashboard
Route::get('/landlord/dashboard', [AdminController::class, 'landlordDashboard'])
    ->name('dashboard.landlord')
    ->middleware('auth');
        Route::get('/dashboard/tenant', [AdminController::class, 'tenantDashboard'])
        ->name('dashboard.tenant')  ->middleware('auth');

        Route::middleware(['auth', 'role:investor'])
    ->get('/dashboard/investor', [AdminController::class, 'investorDashboard'])
    ->name('dashboard.investor');

/*
|--------------------------------------------------------------------------
| PARTNERS (INVESTORS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* ======================
       INVESTOR SELF ACTIONS
    ====================== */

    Route::get('/partner/register', [PartnerController::class, 'create'])
        ->name('partners.create');

    Route::post('/partner/register', [PartnerController::class, 'store'])
        ->name('partners.store');

    Route::get('/partner/{partner:slug}', [PartnerController::class, 'show'])
        ->name('partners.show');

    Route::get('/partner/{partner:slug}/edit', [PartnerController::class, 'edit'])
        ->name('partners.edit');

    Route::put('/partner/{partner:slug}', [PartnerController::class, 'update'])
        ->name('partners.update');

    Route::get('/partner/{partner:slug}/agreement', [PartnerController::class, 'downloadAgreement'])
        ->name('partners.agreement.download');

    // Route::post('/partner/{partner}/sync-qbo', [PartnerController::class, 'syncWithQBO'])
    //     ->name('partners.sync.qbo');
    Route::get('/partner_kyc', [PartnerController::class, 'investor_kyc'])->name('partners.investor_kyc');
    Route::post('/partners/{partner:slug}/sync-qbo', [PartnerController::class, 'syncWithQBO'])
     ->name('partners.sync-qbo');

         Route::get('partners/{partner}/agreement/download', [PartnerController::class, 'downloadAgreement'])
        ->name('partners.downloadAgreement');

    /* ======================
       ADMIN ONLY
    ====================== */

    Route::middleware(['role:admin'])->group(function () {

        Route::get('/partners', [PartnerController::class, 'index'])
            ->name('partners.index');

        Route::patch('/partners/{partner}/status', [PartnerController::class, 'updateStatus'])
            ->name('partners.update.status');

        Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])
            ->name('partners.destroy');

    });

});

    // routes/web.php
Route::put('/users/{user}/approve', [UserController::class, 'approve'])
    ->name('users.approve')
    ->middleware(['auth']);
        Route::resource('users', UserController::class);


Route::get('/admin/security/login-attempts',
    [\App\Http\Controllers\Admin\LoginAttemptController::class, 'index']
)->middleware(['auth', 'role:admin'])
 ->name('admin.login-attempts');




Route::post('/investment/pay', [PropertyInvestmentController::class, 'wirepickPayment'])
    ->name('wirepick.invest');
        Route::post('/property-invest/checkout', [PropertyInvestmentController::class, 'checkout'])->name('property-invest.checkout');
    Route::get('/property-invest/success', [PropertyInvestmentController::class, 'success'])->name('property-invest.success');
    Route::get('/property-invest/cancel', [PropertyInvestmentController::class, 'cancel'])->name('property-invest.cancel');
    Route::get('/property-invest/thank-you', [PropertyInvestmentController::class, 'thankYou'])->name('property-invest.thank-you');


Route::get('/pay',  [WirepickPaymentController::class, 'showForm'])->name('wp.form');
// Route::post('/pay', [WirepickPaymentController::class, 'process'])
//     ->name('wp.process');

Route::post('/pay_plus', [WirepickPaymentController::class, 'trying'])->name('wp.trying');

// web.php
Route::get('/properties/{property}/checkout',
    [WirepickPaymentController::class, 'checkout']
)->middleware('auth')->name('wirepick.checkout');

Route::post('/wirepick/process',
    [WirepickPaymentController::class, 'process']
)->middleware('auth')->name('wp.process');


// routes/web.php
Route::middleware(['auth'])
    ->get('/payments', [PaymentController::class, 'index'])
    ->name('payments.index');


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])
    ->name('privacy.show');


    Route::get('/web/privacy-policy', [PrivacyPolicyController::class, 'show_web'])
    ->name('privacy.show_web');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('admin/privacy')
    ->name('admin.privacy.')
    ->group(function () {

        Route::get('/', [PrivacyPolicyController::class, 'index'])->name('index');
        Route::get('/create', [PrivacyPolicyController::class, 'create'])->name('create');
        Route::post('/', [PrivacyPolicyController::class, 'store'])->name('store');
        Route::get('/{privacyPolicy}/edit', [PrivacyPolicyController::class, 'edit'])->name('edit');
        Route::put('/{privacyPolicy}', [PrivacyPolicyController::class, 'update'])->name('update');
        Route::delete('/{privacyPolicy}', [PrivacyPolicyController::class, 'destroy'])->name('destroy');
    });

















    //roles and permissions
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::get('role/{roleId}/add-permission', [RoleController::class, 'addPermissionToRole']);
    Route::put('role/{roleId}/add-permission', [RoleController::class, 'givePermissionToRole']);

//users
    Route::resource('users', UserController::class);
Route::get('/support', [SupportController::class, 'index'])
    ->name('support.index')
    ->middleware(['auth']);
    // routes/web.php
Route::put('/users/{user}/approve', [UserController::class, 'approve'])
    ->name('users.approve')
    ->middleware(['auth']);

    //dashboard

       Route::get('/dashboard/{property?}', [AdminController::class, 'index'])
    ->name('dashboard.index');
        Route::post('/dashboard/logout', [AdminController::class, 'logout'])->name('dashboard.logout');

// Route::get('/buildings/create', [AdminController::class, 'create'])
//      ->name('buildings.create');




Route::prefix('properties/{property:slug}')->group(function () {
    Route::get('users', [PropertyUserController::class, 'index'])->name('property.users.index');
    Route::get('users/create', [PropertyUserController::class, 'create'])->name('property.users.create');
    Route::post('users', [PropertyUserController::class, 'store'])->name('property.users.store');

    // ✅ Edit & Update routes
    Route::get('users/{user}/edit', [PropertyUserController::class, 'edit'])->name('property.users.edit');
    Route::put('users/{user}', [PropertyUserController::class, 'update'])->name('property.users.update');

    // ✅ Delete and Show routes
    Route::delete('users/{user}', [PropertyUserController::class, 'destroy'])->name('property.users.destroy');
    Route::get('users/{user:slug}', [PropertyUserController::class, 'show'])->name('property.users.show');
});

    Route::prefix('properties/{property:slug}')->group(function () {
        Route::resource('units', PropertyUnitController::class)
            ->parameters(['units' => 'unit:slug']) // use slug for Unit binding
            ->names('property.units');
    });

Route::prefix('properties/{property:slug}')->group(function () {
    Route::get('agreements', [PropertyLeaseAgreementController::class, 'index'])->name('property.agreements.index');
    Route::get('agreements/create', [PropertyLeaseAgreementController::class, 'create'])->name('property.agreements.create');
    Route::post('agreements', [PropertyLeaseAgreementController::class, 'store'])->name('property.agreements.store');
    Route::get('agreements/{agreement:slug}', [PropertyLeaseAgreementController::class, 'show'])->name('property.agreements.show');
    Route::get('agreements/{agreement:slug}/edit', [PropertyLeaseAgreementController::class, 'edit'])->name('property.agreements.edit');
    Route::put('agreements/{agreement:slug}', [PropertyLeaseAgreementController::class, 'update'])->name('property.agreements.update');
    Route::delete('agreements/{agreement:slug}', [PropertyLeaseAgreementController::class, 'destroy'])->name('property.agreements.destroy');
});


Route::middleware('auth')->group(function () {

    Route::get(
        'properties/{property:slug}/lease-template',
        [PropertyLeaseTemplateController::class, 'edit']
)->name('property.lease-template.edit');

    Route::put(
        'properties/{property:slug}/lease-template',
        [PropertyLeaseTemplateController::class, 'update']
    )->name('property.lease-template.update');

});

});

Route::prefix('properties/{property}')
    ->middleware('auth','approved')
    ->group(function () {

        // List
        Route::get('expenses', [PropertyExpensesController::class, 'index'])
            ->name('property.expenses.index');

        // Store
        Route::post('expenses', [PropertyExpensesController::class, 'store'])
            ->name('property.expenses.store');

        // Show
        Route::get('expenses/{expense}', [PropertyExpensesController::class, 'show'])
            ->name('property.expenses.show');

        // Edit
        Route::get('expenses/{expense}/edit', [PropertyExpensesController::class, 'edit'])
            ->name('property.expenses.edit');

        // Update
        Route::put('expenses/{expense}', [PropertyExpensesController::class, 'update'])
            ->name('property.expenses.update');

        // Delete
        Route::delete('expenses/{expense}', [PropertyExpensesController::class, 'destroy'])
            ->name('property.expenses.destroy');
    });


Route::prefix('properties/{property}')
    ->middleware('auth','approved')
    ->group(function () {

        Route::get('payments', [PropertyPaymentsController::class, 'index'])
            ->name('property.payments.index');

        Route::post('payments', [PropertyPaymentsController::class, 'store'])
            ->name('property.payments.store');

        Route::put('payments/{payment}', [PropertyPaymentsController::class, 'update'])
            ->name('property.payments.update');

        Route::delete('payments/{payment}', [PropertyPaymentsController::class, 'destroy'])
            ->name('property.payments.destroy');

        Route::get(
            'leases/{lease}/payments/{month}/receipt',
            [PropertyPaymentsController::class, 'receipt']
        )->name('property.payments.receipt');
    });

    Route::post(
    '/properties/{property}/payments/mobile-money',
    [PropertyPaymentsController::class, 'payWithMobileMoney']
)->name('property.payments.mobile');

Route::middleware(['auth'])->group(function () {

    // 📤 Export tenants (CSV)
    Route::get(
        '/properties/{property}/users-export/csv',
        [PropertyUserController::class, 'exportCsv']
    )->name('property.users.export.csv');

    // 📥 Import tenants (CSV)
    Route::post(
        '/properties/{property}/users-import/csv',
        [PropertyUserController::class, 'importCsv']
    )->name('property.users.import.csv');

});


Route::middleware(['auth'])->group(function () {

    Route::get(
        '/properties/{property}/units-export/csv',
        [PropertyUnitController::class, 'exportCsv']
    )->name('property.units.export.csv');

    Route::post(
        '/properties/{property}/units-import/csv',
        [PropertyUnitController::class, 'importCsv']
    )->name('property.units.import.csv');

});

// routes/web.php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])
        ->name('admin.backup.index');

    Route::post('/backup/run', [\App\Http\Controllers\Admin\BackupController::class, 'run'])
        ->name('admin.backup.run');
});



    Route::get('/kyc/pending', function () {
    return view('kyc.pending');
})->name('kyc.pending');



Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/settings/login-hero', [LoginHeroController::class, 'edit'])
        ->name('login-hero.edit');

    Route::post('/settings/login-hero', [LoginHeroController::class, 'store'])
        ->name('login-hero.store');
});



Route::put(
    'properties/{property:slug}/agreements/{agreement:slug}/status',
    [PropertyLeaseAgreementController::class, 'updateStatus']
)->name('property.agreements.updateStatus');

Route::prefix('properties/{property}')
    ->middleware('auth','approved')
    ->group(function () {

        Route::get('reports', [PropertyReportController::class, 'index'])
            ->name('property.reports.index');

        Route::get('reports/pdf', [PropertyReportController::class, 'exportPdf'])
            ->name('property.reports.pdf');
    });







/*
|--------------------------------------------------------------------------
| Public Tenant Registration (NO middleware)
|--------------------------------------------------------------------------
*/

// Public tenant signup form
Route::get(
    '/properties/{property:slug}/register',
    [PropertyUserController::class, 'publicCreate']
)->name('property.users.public.create');

// Public tenant signup submit
Route::post(
    '/properties/{property:slug}/register',
    [PropertyUserController::class, 'publicStore']
)->name('property.users.public.store');

// Public lease apply (after signup)
Route::get(
    '/properties/{property:slug}/lease/apply',
    [PropertyLeaseAgreementController::class, 'publicCreate']
)->name('property.agreements.public.create');

Route::post(
    '/properties/{property:slug}/lease/apply',
    [PropertyLeaseAgreementController::class, 'publicStore']
)->name('property.agreements.public.store');


Route::get('/properties/{property:slug}/agreements/{agreement:slug}/pdf',
    [PropertyLeaseAgreementController::class, 'pdf']
)->name('property.agreements.pdf');

Route::get('/properties/{property:slug}/agreements/{agreement:slug}/download',
    [PropertyLeaseAgreementController::class, 'download']
)->name('property.agreements.download');


    Route::middleware(['auth'])
    ->prefix('admin/system-settings')
    ->name('admin.settings.')
    ->group(function () {

        // ⚙️ Settings Hub (landing page)
        Route::get('/', function () {
            return view('admin.system-settings.hub');
        })->name('hub');

        // ⚙️ General settings
        Route::get('/general', [SystemSettingsController::class, 'index'])
            ->name('index');

        Route::post('/update', [SystemSettingsController::class, 'update'])
            ->name('update');
    });

require __DIR__.'/auth.php';
