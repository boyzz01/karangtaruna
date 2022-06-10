<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function get_all_kecamatan(){
        return response()->json(Kecamatan::all());
    }
}
