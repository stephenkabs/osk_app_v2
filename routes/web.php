<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyExpensesController;
use App\Http\Controllers\PropertyLeaseAgreementController;
use App\Http\Controllers\PropertyLeaseTemplateController;
use App\Http\Controllers\PropertyPaymentsController;
use App\Http\Controllers\PropertyReportController;
use App\Http\Controllers\PropertyUnitController;
use App\Http\Controllers\PropertyUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
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




// Landlord dashboard
Route::get('/landlord/dashboard', [AdminController::class, 'landlordDashboard'])
    ->name('dashboard.landlord')
    ->middleware('auth');


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


require __DIR__.'/auth.php';
