<?php

use App\Http\Controllers\ApiController;
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

Route::get('kecamatan',[ApiController::class, 'get_all_kecamatan']);

Route::post('/add_anggota', [ApiController::class,'addAnggota'] );

Route::post('/login', [ApiController::class,'check_user'] );
Route::post('/cek_verif', [ApiController::class,'check_verif'] );

Route::get('detail_anggota/{id}',[ApiController::class, 'detail_anggota']);

Route::post('edit_profil',[ApiController::class, 'edit_profil']);

Route::post('edit_foto',[ApiController::class, 'edit_foto']);