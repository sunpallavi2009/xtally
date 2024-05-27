<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocietyController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\OtpRequestController;
use App\Http\Controllers\VoucherEntryController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/get-data', [RoleController::class, 'getData'])->name('roles.get-data');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles/store', [RoleController::class, 'store'])->name('roles.store');

    Route::get('/otp', [OtpRequestController::class, 'index'])->name('otp');
    Route::post('/send-otp', [OtpRequestController::class, 'sendOtp'])->name('sendOtp');

    Route::get('/ledgers', [LedgerController::class, 'index'])->name('ledgers.index');
    Route::post('ledgers/upload', [LedgerController::class, 'upload'])->name('ledgers.upload');

    Route::get('/society', [SocietyController::class, 'index'])->name('society.index');
    Route::get('society/get-data', [SocietyController::class, 'getData'])->name('society.get-data');

    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('members/get-data', [MemberController::class, 'getData'])->name('members.get-data');

    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::get('vouchers/get-data', [VoucherController::class, 'getData'])->name('vouchers.get-data');

    Route::get('/voucherEntry', [VoucherEntryController::class, 'index'])->name('voucherEntry.index');
    Route::get('voucherEntry/get-data', [VoucherEntryController::class, 'getData'])->name('voucherEntry.get-data');

});

require __DIR__.'/auth.php';
