<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {

        $anggota =  DB::table('anggota')->get();
        $umkm = DB::table('umkm')->get();
        return view('dashboard', ['data' => $anggota, 'umkm' => $umkm]);
    }
}
