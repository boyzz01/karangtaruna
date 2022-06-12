<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\Anggota;
use App\Models\Kecamatan;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    //
    public function get_all_kecamatan(){
        return response()->json(Kecamatan::all());
    }

    public function check_verif(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user==null){
            return response()
            ->json([
                'success' => false,
                'data' =>"Email Tidak ditemukan"
            ]);
        }else{
            if($user->email_token==$request->token){
                $user->update([
        
                    'email_verified' => 1,
                    'email_verified_at' => Carbon::now(),
                    'email_token' => ''
            
                   ]);
                return response()
                ->json([
                    'success' => true,
                    'data' =>""
                ]);
            }else{
                return response()
                ->json([
                    'success' => false,
                    'data' =>"Kode Verifikasi Salah"
                ]);
            }
        }
    }
  
    public function check_user(Request $request)
    {

       
        $credential = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email',$request->email)->first();

        if($user==null){
            return response()
            ->json([
                'success' => false,
                'data' =>"Email Belum Terdaftar"
            ]);
        }else{

            if($user->email_verified == 1){
                if (Auth::guard('anggota')->attempt($credential)){
                    return response()
                    ->json([
                        'success' => true,
                        'data' =>$user
                    ]);
                }else{
                    return response()
                    ->json([
                        'success' => false,
                        'data' =>"Username Atau Password Salah"
                    ]);
                }
            }else{
                return response()
                ->json([
                    'success' => false,
                    'data' =>"0"
                ]);
            }
        }

    
      
      
    }
    public function addAnggota(Request $request){

        $anggota = User::where('email',$request->email)->first();
        $ceknik = Anggota::where('nik',$request->nik)->first();

        if($anggota!=null){
            return response()
            ->json([
                'success' => false,
                'data' =>"Email Sudah Terdaftar"
            ]);
        }else{
            if($ceknik!=null){
                return response()
                ->json([
                    'success' => false,
                    'data' =>"NIK Sudah Terdaftar"
                ]);
            }else{
                DB::beginTransaction();
                try{
                    $kecamatan = Kecamatan::where('nama',$request->kecamatan)->first();
                    $no = DB::table('counter')->where('id','=',1)->first();
            
                    $user = new User();
                    $user->email = $request->email;
                    $user->password=$request->password;
                    $user->name=$request->nama;
                    $user->email_token =sprintf("%06d", mt_rand(1, 999999));
                    $user->save(); 
        
        
                    $nomor = "1271.".$kecamatan->kode.".".str_pad($no->value, 6, '0', STR_PAD_LEFT);
                    $temp = new Anggota();
                    $temp->kta=$nomor;
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
                    $tes = $no->value+1;
                    DB::update("update counter set value = $tes where id = 1");

                    Mail::to($user->email)->send(new VerificationEmail($user));

                    DB::commit();
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
      
      
    }
}
