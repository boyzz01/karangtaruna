<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function get_all_kecamatan(){
        return response()->json(Kecamatan::all());
    }

    public function addAnggota(Request $request){
        $kecamatan = Kecamatan::where('nama',$request->kecamatan)->first();

 

        $temp = new Anggota();
        $temp->kta=$request->kta;
        $temp->id_user ="";
        $temp->email=$request->email; 
        $temp->nama=$request->nama;
        $temp->nik=$request->nik;
        $temp->ttl=$request->ttl;
        $temp->agama=$request->agama;
        $temp->jenis_kelamin=$request->jk;
        $temp->pekerjaan=$request->pekerjaan;
        $temp->pendidikan=$request->pendidikan;
        $temp->alamat=$request->alamat;
        $temp->kode_kecamatan=$kecamatan->kode;
        $temp->kelurahan=$request->kelurahan;
        $temp->lingkungan=$request->lingkungan;
        $temp->no_hp=$request->no_hp;
        $temp->tingkat="";
        $temp->jabatan_provinsi=$request->j_prov;
        $temp->jabatan_kota=$request->j_kota;
        $temp->jabatan_kecamatan=$request->j_kecamatan;
        $temp->jabatan_kelurahan=$request->j_kelurahan;
        $temp->ktp="";
        $temp->ttd="";
        $temp->foto="";

        $saved = $temp->save();

        if(!$saved){
            return response()
            ->json([
                'success' => false,
                'data' =>"Error"
            ]);
          }else{

        
            return response()
            ->json([
                'success' => true,
                'data' =>"UMKM Berhasil ditambah"
            ]);
          }
    }
}
