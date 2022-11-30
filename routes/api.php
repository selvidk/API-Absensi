<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('/registrasi', [App\Http\Controllers\Api\v1\AuthController::class, 'registrasi']);
    Route::post('/login', [App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    Route::post('/lupa_password/email', [App\Http\Controllers\Api\v1\AuthController::class, 'lupaPassword']);
    Route::post('/lupa_password/cek_otp', [App\Http\Controllers\Api\v1\AuthController::class, 'cekOTP']);
    Route::post('/reset_password', [App\Http\Controllers\Api\v1\AuthController::class, 'resetPassword']);
    Route::post('/cek_no_hp', [App\Http\Controllers\Api\v1\AuthController::class, 'cekNoHp']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('profile')->group(function () {
        Route::get('', [App\Http\Controllers\Api\v1\ProfileController::class, 'getProfile']);

        Route::get('/biodata', [App\Http\Controllers\Api\v1\ProfileController::class, 'getBiodata']);
        Route::post('/biodata/update', [App\Http\Controllers\Api\v1\ProfileController::class, 'updateBiodata']);
        Route::post('/biodata/update/pelengkap', [App\Http\Controllers\Api\v1\ProfileController::class, 'updateDataLain']);

        Route::get('/wali', [App\Http\Controllers\Api\v1\ProfileController::class, 'getWali']);
        Route::post('/wali/update', [App\Http\Controllers\Api\v1\ProfileController::class, 'updateWali']);

        Route::get('/riwayat_pendidikan', [App\Http\Controllers\Api\v1\ProfileController::class, 'getPendidikan']);
        Route::post('/riwayat_pendidikan/update', [App\Http\Controllers\Api\v1\ProfileController::class, 'updatePendidikan']);

        Route::post('/password/update', [App\Http\Controllers\Api\v1\ProfileController::class, 'updatePassword']);
    });

    Route::post('/hasil_belajar', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getHasilBelajar']);
    Route::post('/proses_absensi', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'prosesAbsensi']);
    Route::get('/absensi', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getAbsensi']);
    Route::post('/absensi/rekap', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'countAbsensi']);

    Route::prefix('data_pendukung')->group(function () {
        Route::get('/kelas', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getKelas']);
        Route::get('/mapel', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getMapel']);
        Route::post('/nilai', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getNilai']);
        Route::post('/pkl', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getPkl']);
        Route::post('/ekskul', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getEkskul']);
        Route::post('/prestasi', [App\Http\Controllers\Api\v1\DataPendukungController::class, 'getPrestasi']);
    });

    Route::get('/logout', [App\Http\Controllers\Api\v1\AuthController::class, 'logout']);
});