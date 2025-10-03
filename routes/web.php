<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\StaffMemberController;
use App\Http\Controllers\MemberHistoryController;
use App\Http\Controllers\CategoryController;
// Login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Superadmin
Route::get('superadmin/dashboard', [LoginController::class, 'superAdminDashboard'])->name('superadmin.dashboard');
Route::post('superadmin/add-company', [LoginController::class, 'addCompany'])->name('superadmin.addCompany');
Route::put('/superadmin/update-company/{id}', [LoginController::class, 'updateCompany'])->name('superadmin.updateCompany');
Route::delete('superadmin/delete-company/{id}', [LoginController::class, 'deleteCompany'])->name('superadmin.deleteCompany');

Route::post('settings/add-superadmin', [AdminController::class, 'addSuperAdmin'])->name('superadmin.add')->middleware('auth');
Route::delete('superadmin/delete/{id}', [AdminController::class, 'deleteSuperAdmin'])->name('superadmin.deleteUser');
// routes/web.php
// Route::put('superadmin/update/{id}', [AdminController::class, 'updateSuperAdmin'])
//     ->name('superadmin.update')
//     ->middleware('auth');


    
// Gym Settings
Route::get('settings', [AdminController::class, 'gymSettings'])->name('gym.settings');
Route::post('settings', [AdminController::class, 'updateGymSettings'])->name('gym.settings.update');

// Members Filter + Renew
Route::get('gym/members/filter', [AdminController::class, 'filterMembers'])->name('gym.members.filter');
Route::post('members/{id}/renew', [AdminController::class, 'renewSubmit'])->name('members.renew');
// All History Page
Route::get('gym/members/history', [MemberHistoryController::class, 'index'])->name('gym.members.history');

// Fetch single member history (for renew modal via ajax)
Route::get('/members/{id}/history', [MemberHistoryController::class, 'memberHistory'])->name('members.memberHistory');

// Members Management
Route::get('members', [MemberController::class, 'index'])->name('gym.members.index');
Route::get('members/create', [MemberController::class, 'create'])->name('gym.members.create');
Route::post('members', [MemberController::class, 'store'])->name('gym.members.store');
Route::get('members/{id}', [MemberController::class, 'show'])->name('gym.members.show');
Route::get('members/{id}/edit', [MemberController::class, 'edit'])->name('gym.members.edit');
Route::put('members/{id}', [MemberController::class, 'update'])->name('gym.members.update');
Route::delete('members/{id}', [MemberController::class, 'destroy'])->name('gym.members.destroy');

// Export Routes
Route::get('members/export/csv', [MemberController::class, 'exportCsv'])->name('gym.members.export.csv');
Route::get('members/export/pdf', [MemberController::class, 'exportPdf'])->name('gym.members.export.pdf');

// Membership Management
Route::get('membership', [AdminController::class, 'gymMembership'])->name('gym.membership');
Route::post('membership/store', [AdminController::class, 'storeMembership'])->name('membership.store');
Route::post('membership/update/{id}', [AdminController::class, 'updateMembership'])->name('membership.update');
Route::post('membership/delete/{id}', [AdminController::class, 'deleteMembership'])->name('membership.delete');
Route::post('membership/save', [AdminController::class, 'saveMembership'])->name('membership.save');

// Membership Category
Route::post('membership/category/add', [AdminController::class, 'addCategory'])->name('membership.category.add');
Route::post('membership/category/delete/{id}', [AdminController::class, 'deleteCategory'])->name('membership.category.delete');

// Membership Installment
Route::post('membership/installment/add', [AdminController::class, 'addInstallment'])->name('membership.installment.add');
Route::post('membership/installment/delete/{id}', [AdminController::class, 'deleteInstallment'])->name('membership.installment.delete');

// Edit Membership Page
Route::get('membership/edit/{id}', [AdminController::class, 'editMembershipPage'])->name('membership.edit');


// Expenses
Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('expenses/{id}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
Route::put('expenses/{id}/update', [ExpenseController::class, 'update'])->name('expenses.update');
Route::post('expenses/{id}/delete', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
Route::get('expenses/expensesreport', [ExpenseController::class, 'expensesreport'])->name('expenses.expensesreport');
Route::get('expenses/export/csv', [ExpenseController::class, 'exportCsv'])->name('expenses.export.csv');


Route::resource('categories', CategoryController::class)->except(['show', 'edit', 'update']);

// Staff Management
Route::get('staff', [StaffMemberController::class, 'index'])->name('gym.staff.index');
Route::post('staff/store', [StaffMemberController::class, 'store'])->name('gym.staff.store');
Route::post('staff/update/{id}', [StaffMemberController::class, 'update'])->name('gym.staff.update');
Route::post('staff/delete/{id}', [StaffMemberController::class, 'destroy'])->name('gym.staff.destroy');

// Reports
Route::get('report', [ReportController::class, 'index'])->name('gym.report');
Route::get('report/download/pdf', [ReportController::class, 'downloadPdf'])->name('gym.report.pdf');
Route::get('report/download/csv', [ReportController::class, 'downloadCsv'])->name('gym.report.csv');
