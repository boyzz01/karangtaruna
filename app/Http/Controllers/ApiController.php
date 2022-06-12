<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kecamatan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    //
    public function get_all_kecamatan(){
        return response()->json(Kecamatan::all());
    }

    public function addAnggota(Request $request){

        DB::beginTransaction();
        try{
            $kecamatan = Kecamatan::where('nama',$request->kecamatan)->first();

    
            $user = new User();
            $user->email = $request->email;
            $user->password=$request->password;

            $user->save(); 


            $temp = new Anggota();
            $temp->kta="";
            $temp->id_user =$user->id;
            $temp->email=$request->email; 
            $temp->nama=$request->nama;
            $temp->nik=$request->nik;
            $temp->ttl=$request->tempatLahir.",".$request->tanggalLahir;
            $temp->agama=$request->agama;
            $temp->jenis_kelamin=$request->jk;
            $temp->pekerjaan=$request->pekerjaaan;
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
            $temp->foto="";

            
            if($request->file('ttd')!=null){
                $ttd = $request->file('ttd')->store('ttd');
                $url = config('app.url');
                $image=$url."/storage/app/". $ttd;
                $temp->ttd = $image;
            }

            if($request->file('foto')!=null){
                $foto = $request->file('foto')->store('foto');
                $url = config('app.url');
                $image=$url."/storage/app/". $foto;
                $temp->foto = $image;
            }

            if($request->file('ktp')!=null){
                $ktp = $request->file('ktp')->store('ktp');
                $url = config('app.url');
                $image=$url."/storage/app/". $ktp;
                $temp->ktp = $image;
            }

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
        catch (Exception $e) {       // Rollback Transaction
            DB::rollback();
            return response()->json([
                'success' => false,
                'data'=>$e
            ]);
            // ada yang error     
        }
      
    }
}
