<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;

// Login
Route::get('admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');

// Superadmin
Route::get('superadmin/dashboard', [LoginController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
Route::post('superadmin/add-company', [LoginController::class, 'addCompany'])->name('superadmin.addCompany');
Route::put('superadmin/update-company/{id}', [LoginController::class, 'updateCompany'])->name('superadmin.updateCompany');
Route::delete('superadmin/delete-company/{id}', [LoginController::class, 'deleteCompany'])->name('superadmin.deleteCompany');

// Gym Dashboard & Sections
Route::prefix('gym/dashboard')->group(function () {
    // Route::get('/', [AdminController::class, 'index'])->name('gym.dashboard');

Route::get('/gym/dashboard/members/filter', [AdminController::class, 'filterMembers'])
     ->name('gym.dashboard.members.filter');
Route::post('/members/{id}/renew', [AdminController::class, 'renewMember'])->name('members.renew');



// Member Management Routes
Route::prefix('members')->name('gym.members.')->group(function () {
    Route::get('/', [MemberController::class, 'index'])->name('index');
    Route::get('create', [MemberController::class, 'create'])->name('create');
    Route::post('/', [MemberController::class, 'store'])->name('store');

    Route::get('{id}', [MemberController::class, 'show'])->name('show');
    Route::get('{id}/edit', [MemberController::class, 'edit'])->name('edit');
    Route::put('{id}', [MemberController::class, 'update'])->name('update');
    Route::delete('{id}', [MemberController::class, 'destroy'])->name('destroy');
    
});





// Export Routes
Route::get('members/export/csv', [MemberController::class, 'exportCsv'])->name('gym.members.export.csv');
Route::get('members/export/pdf', [MemberController::class, 'exportPdf'])->name('gym.members.export.pdf');





Route::get('/membership', [AdminController::class, 'gymMembership'])->name('gym.membership');

Route::post('/membership/store', [AdminController::class, 'storeMembership'])->name('membership.store');
Route::post('/membership/store', [AdminController::class, 'store'])->name('membership.store');
Route::post('/membership/update/{id}', [AdminController::class, 'updateMembership'])->name('membership.update');


Route::post('/membership/delete/{id}', [AdminController::class, 'deleteMembership'])->name('membership.delete');
Route::post('/membership/save', [AdminController::class, 'saveMembership'])->name('membership.save');


Route::post('/membership/category/add', [AdminController::class, 'addCategory'])->name('membership.category.add');
Route::post('/membership/category/delete/{id}', [AdminController::class, 'deleteCategory'])->name('membership.category.delete');

Route::post('/membership/installment/add', [AdminController::class, 'addInstallment'])->name('membership.installment.add');
Route::post('/membership/installment/delete/{id}', [AdminController::class, 'deleteInstallment'])->name('membership.installment.delete');
// Edit Membership page (new blade)
Route::get('/membership/edit/{id}', [AdminController::class, 'editMembershipPage'])->name('membership.edit');

    
    
Route::get('/settings', [AdminController::class, 'gymSettings'])->name('gym.settings');
Route::post('/settings', [AdminController::class, 'updateGymSettings'])->name('gym.settings.update');
});
Route::post('/settings/add-superadmin', [AdminController::class, 'addSuperAdmin'])
    ->name('superadmin.add')
    ->middleware('auth'); // Optional: restrict to logged-in superadmins
Route::delete('/superadmin/delete/{id}', [AdminController::class, 'deleteSuperAdmin'])->name('superadmin.deleteUser');


use App\Http\Controllers\ExpenseController;

Route::middleware(['web'])->group(function () {
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{id}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
   Route::put('expenses/{id}/update', [ExpenseController::class, 'update'])->name('expenses.update');

    Route::post('/expenses/{id}/delete', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Future ready endpoints for reports/exports:
    Route::get('/expenses/expensesreport', [ExpenseController::class, 'expensesreport'])->name('expenses.expensesreport');         // report filter page
    Route::get('/expenses/export/csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export.csv'); // export CSV
});



// Logout Route
use App\Http\Controllers\StaffMemberController;

Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::prefix('gym')->name('gym.')->group(function() {

    // Staff Management
    Route::get('/staff', [StaffMemberController::class, 'index'])->name('staff.index');

    // Create new staff
    Route::post('/staff/store', [StaffMemberController::class, 'store'])->name('staff.store');

    // Update existing staff
    Route::post('/staff/update/{id}', [StaffMemberController::class, 'update'])->name('staff.update');

    // Delete staff
    Route::post('/staff/delete/{id}', [StaffMemberController::class, 'destroy'])->name('staff.destroy');

});






Route::prefix('gym')->group(function(){
    Route::get('/report', [ReportController::class, 'index'])->name('gym.report');
    Route::get('/report/download/pdf', [ReportController::class, 'downloadPdf'])->name('gym.report.pdf');
    Route::get('/report/download/csv', [ReportController::class, 'downloadCsv'])->name('gym.report.csv');
});
