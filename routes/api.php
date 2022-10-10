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

Route::get('kecamatan', [ApiController::class, 'get_all_kecamatan']);
Route::get('kelurahan', [ApiController::class, 'get_all_kelurahan']);
Route::get('kategori', [ApiController::class, 'get_kategori']);
Route::get('jenis', [ApiController::class, 'get_jenis']);
Route::get('umkm', [ApiController::class, 'get_umkm']);
Route::get('berita', [ApiController::class, 'get_berita']);
Route::get('limitberita', [ApiController::class, 'limit_berita']);
Route::get('detail_berita/{id}', [ApiController::class, 'detail_berita']);

Route::post('/add_anggota', [ApiController::class, 'addAnggota']);
Route::post('/add_umkm', [ApiController::class, 'addUmkm']);

Route::post('/login', [ApiController::class, 'check_user']);
Route::post('/cek_verif', [ApiController::class, 'check_verif']);

Route::get('detail_anggota/{id}', [ApiController::class, 'detail_anggota']);
Route::get('detail_umkm/{id}', [ApiController::class, 'detail_umkm']);
Route::post('edit_profil', [ApiController::class, 'edit_profil']);

Route::post('edit_foto', [ApiController::class, 'edit_foto']);

Route::post('/add_produk', [ApiController::class, 'add_produk']);
Route::get('produk_umkm/{id}', [ApiController::class, 'get_produk_umkm']);
Route::get('detail_produk/{id}', [ApiController::class, 'detail_produk']);

Route::get('kelurahan/{id}', [ApiController::class, 'get_kelurahan']);
