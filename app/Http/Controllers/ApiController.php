<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\Anggota;
use App\Models\Berita;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Produk;
use App\Models\Umkm;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    //

    public function get_berita()
    {
        return response()->json(Berita::all());
    }

    public function limit_berita()
    {
        $data =  DB::table("berita")->limit(3)->get();
        return response()->json($data);
    }

    public function detail_berita($id)
    {
        return response()->json(Berita::where("id", $id)->first());
    }

    public function detail_anggota($id)
    {
        return response()->json(Anggota::where("id_user", $id)->first());
    }
    public function get_all_kecamatan()
    {
        return response()->json(Kecamatan::all());
    }

    public function get_all_kelurahan()
    {
        return response()->json(Kelurahan::all());
    }

    public function check_verif(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return response()
                ->json([
                    'success' => false,
                    'data' => "Email Tidak ditemukan"
                ]);
        } else {
            if ($user->email_token == $request->token) {
                $user->update([

                    'email_verified' => 1,
                    'email_verified_at' => Carbon::now(),
                    'email_token' => ''

                ]);
                return response()
                    ->json([
                        'success' => true,
                        'data' => ""
                    ]);
            } else {
                return response()
                    ->json([
                        'success' => false,
                        'data' => "Kode Verifikasi Salah"
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

        $user = User::where('email', $request->email)->first();

        if ($user == null) {
            return response()
                ->json([
                    'success' => false,
                    'data' => "Email Belum Terdaftar"
                ]);
        } else {

            if ($user->email_verified == 1) {
                if (Auth::guard('anggota')->attempt($credential)) {
                    return response()
                        ->json([
                            'success' => true,
                            'data' => $user
                        ]);
                } else {
                    return response()
                        ->json([
                            'success' => false,
                            'data' => "Username Atau Password Salah"
                        ]);
                }
            } else {
                return response()
                    ->json([
                        'success' => false,
                        'data' => "0"
                    ]);
            }
        }
    }
    public function addAnggota(Request $request)
    {

        $anggota = User::where('email', $request->email)->first();
        $ceknik = Anggota::where('nik', $request->nik)->first();

        if ($anggota != null) {
            return response()
                ->json([
                    'success' => false,
                    'data' => "Email Sudah Terdaftar"
                ]);
        } else {
            if ($ceknik != null) {
                return response()
                    ->json([
                        'success' => false,
                        'data' => "NIK Sudah Terdaftar"
                    ]);
            } else {
                DB::beginTransaction();
                try {
                    $kecamatan = Kecamatan::where('nama', $request->kecamatan)->first();
                    $kelurahan = Kelurahan::where('nama', $request->kelurahan)->first();
                    $no = DB::table('counter')->where('id', '=', 1)->first();

                    $user = new User();
                    $user->email = $request->email;
                    $user->password = $request->password;
                    $user->name = $request->nama;
                    $user->email_token = sprintf("%06d", mt_rand(1, 999999));
                    $user->save();


                    if ($request->pj == "Kota Medan") {
                        $nomor = str_pad($no->value, 5, '0', STR_PAD_LEFT) . "0201";
                    } else if ($request->pj == "Warga") {
                        $nomor = "-";
                    } else {
                        $kel = DB::table("ms_kelurahan")->where('nama', $request->pj)->first();
                        if ($kel != null) {
                            $kodekel = $kel->kode_kelurahan;
                            $kec = DB::table("ms_kecamatan")->where('id', $kel->id_kecamatan)->first();
                            $kodekec = $kec->kode;
                            $nomor = str_pad($no->value, 5, '0', STR_PAD_LEFT) . "0201" . $kodekec . $kodekel;
                        } else {
                            $kec = DB::table("ms_kecamatan")->where('nama', $request->pj)->first();
                            $nomor = str_pad($no->value, 5, '0', STR_PAD_LEFT) . "0201" . $kec->kode;
                        }
                    }


                    // $kodekec = "";
                    // $kodekel = "";

                    // $nomor = str_pad($no->value, 5, '0', STR_PAD_LEFT) . "0201";
                    // $kel = DB::table("ms_kelurahan")->where('nama', $request->pj)->first();


                    //$nomor = str_pad($no->value, 5, '0', STR_PAD_LEFT) . "0201" . $kecamatan->kode . $kelurahan->kode_kelurahan;
                    //  $nomor = "1271.".$kecamatan->kode.".".$kelurahan->kode_kelurahan.".".str_pad($no->value, 6, '0', STR_PAD_LEFT);
                    $temp = new Anggota();
                    $temp->kta = $nomor;
                    $temp->id_user = $user->id;
                    $temp->email = $request->email;
                    $temp->nama = $request->nama;
                    $temp->nik = $request->nik;
                    $temp->ttl = $request->tempatLahir . "," . $request->tanggalLahir;
                    $temp->agama = $request->agama;
                    $temp->jenis_kelamin = $request->jk;
                    $temp->pekerjaan = $request->pekerjaaan;
                    $temp->pendidikan = $request->pendidikan;
                    $temp->alamat = $request->alamat;
                    $temp->kecamatan = $request->kecamatan;
                    $temp->kode_kecamatan = $kecamatan->kode;
                    $temp->kelurahan = $request->kelurahan;
                    $temp->lingkungan = $request->lingkungan;
                    $temp->no_hp = $request->no_hp;
                    $temp->tingkat = $request->pj;
                    $temp->jabatan = $request->posisi;
                    $temp->ktp = "";
                    $temp->foto = "";


                    if ($request->file('ttd') != null) {
                        $ttd = $request->file('ttd')->store('ttd');
                        $url = config('app.url');
                        $image = $url . "/storage/app/" . $ttd;
                        $temp->ttd = $image;
                    }

                    if ($request->file('foto') != null) {
                        $foto = $request->file('foto')->store('foto');
                        $url = config('app.url');
                        $image = $url . "/storage/app/" . $foto;
                        $temp->foto = $image;
                    }

                    if ($request->file('ktp') != null) {
                        $ktp = $request->file('ktp')->store('ktp');
                        $url = config('app.url');
                        $image = $url . "/storage/app/" . $ktp;
                        $temp->ktp = $image;
                    }

                    $saved = $temp->save();
                    $tes = $no->value + 1;
                    DB::update("update counter set value = $tes where id = 1");

                    Mail::to($user->email)->send(new VerificationEmail($user));

                    DB::commit();
                    if (!$saved) {
                        return response()
                            ->json([
                                'success' => false,
                                'data' => "Error"
                            ]);
                    } else {
                        return response()
                            ->json([
                                'success' => true,
                                'data' => "Sukses"
                            ]);
                    }
                } catch (Exception $e) {       // Rollback Transaction
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'data' => $e
                    ]);
                    // ada yang error     
                }
            }
        }
    }

    public function edit_profil(Request $request)
    {
        try {
            Anggota::where('id_user', $request->id_user)
                ->update([
                    'nama' => $request->nama,
                    'ttl' => $request->tempatLahir . "," . $request->tanggalLahir,
                    'agama' => $request->agama,
                    'jenis_kelamin' => $request->jk,
                    'pekerjaan' => $request->pekerjaaan,
                    'pendidikan' => $request->pendidikan,
                    'alamat' => $request->alamat,
                    'kelurahan' => $request->kelurahan,
                    'lingkungan' => $request->lingkungan,
                    'no_hp' => $request->no_hp,
                    // 'jabatan_provinsi' => $request->j_prov,
                    // 'jabatan_kota' => $request->j_kota,
                    // 'jabatan_kecamatan' => $request->j_kecamatan,
                    // 'jabatan_kelurahan' => $request->j_kelurahan
                ]);

            return response()
                ->json([
                    'success' => true,
                    'data' => "ok"
                ]);
        } catch (QueryException $e) {
            //var_dump($e->getMessage())

            return response()
                ->json([
                    'success' => false,
                    'data' => $e->getMessage()
                ]);
        }
        // if(!$saved){
        //             return response()
        //             ->json([
        //                 'success' => false,
        //                 'data' =>"Error"
        //             ]);
        //         }else{
        //             return response()
        //             ->json([
        //                 'success' => true,
        //                 'data' =>"UMKM Berhasil ditambah"
        //             ]);
        //         }
        // DB::beginTransaction();
        // try{




        //     $saved = Anggota::where('id_user',$request->id_user)
        //     ->update(['nama'=>$request->nama,
        //     'ttl'=>$request->tempatLahir.",".$request->tanggalLahir,
        //     'agama'=>$request->agama,
        //     'jenis_kelamin'=>$request->jk,
        //     'pekerjaan'=>$request->pekerjaaan,
        //     'pendidikan'=>$request->pendidikan,
        //     'alamat'=>$request->alamat,
        //     'kelurahan'=>$request->kelurahan,
        //     'lingkungan'=>$request->lingkungan,
        //     'no_hp'=>$request->no_hp,
        //     'tingkat'=>"",
        //     'jabatan_provinsi'=>$request->j_prov,
        //     'jabatan_kota'=>$request->j_kota,
        //     'jabatan_kecamatan'=>$request->j_kecamatan,
        //     'jabatan_kelurahan'=>$request->j_kelurahan]);




        //     DB::commit();
        //     if(!$saved){
        //         return response()
        //         ->json([
        //             'success' => false,
        //             'data' =>"Error"
        //         ]);
        //     }else{
        //         return response()
        //         ->json([
        //             'success' => true,
        //             'data' =>"UMKM Berhasil ditambah"
        //         ]);
        //     }
        // }
        // catch (Exception $e) {       // Rollback Transaction
        //     DB::rollback();
        //     return response()->json([
        //         'success' => false,
        //         'data'=>$e
        //     ]);
        //     // ada yang error     
        // }
    }

    public function edit_foto(Request $request)
    {
        DB::beginTransaction();
        try {

            if ($request->file('foto') != null) {
                $foto = $request->file('foto')->store('foto');
                $url = config('app.url');
                $image = $url . "/storage/app/" . $foto;
                $temp = $image;
            }

            $saved = Anggota::where('id_user', $request->id_user)
                ->update(['foto' => $temp]);



            DB::commit();
            if (!$saved) {
                return response()
                    ->json([
                        'success' => false,
                        'data' => "Error"
                    ]);
            } else {
                return response()
                    ->json([
                        'success' => true,
                        'data' => "Foto Berhasil diubah"
                    ]);
            }
        } catch (Exception $e) {       // Rollback Transaction
            DB::rollback();
            return response()->json([
                'success' => false,
                'data' => $e
            ]);
            // ada yang error     
        }
    }

    public function get_kategori()
    {
        $data = DB::table("ms_kategori")->get();
        return response()->json($data);
    }

    public function get_jenis()
    {
        $data = DB::table("ms_jenis")->get();
        return response()->json($data);
    }

    public function addUmkm(Request $request)
    {
        DB::beginTransaction();
        try {

            $umkm = array();
            $umkm['nama'] = $request->nama;
            $umkm['alamat'] = $request->alamat;
            $umkm['bidang'] = $request->bidang;
            $umkm['nib'] = $request->nib;
            $umkm['modal'] = $request->modal;
            $umkm['id_user'] = $request->iduser;

            if ($request->file('foto') != null) {
                $foto = $request->file('foto')->store('foto');
                $url = config('app.url');
                $image = $url . "/storage/app/" . $foto;
                $umkm['foto'] = $image;
            }

            Umkm::UpdateOrCreate(['id_user' => $request->iduser], $umkm);


            DB::commit();
            return response()
                ->json([
                    'success' => true,
                    'data' => "Sukses"
                ]);
        } catch (Exception $e) {       // Rollback Transaction
            DB::rollback();
            return response()->json([
                'success' => false,
                'data' => $e
            ]);
            // ada yang error     
        }
    }

    public function detail_umkm($id)
    {
        return response()->json(Umkm::where("id_user", $id)->first());
    }


    public function get_umkm()
    {
        $data = DB::table("umkm")->get();
        return response()->json($data);
    }

    public function add_produk(Request $request)
    {

        $foto = $request->file('foto')->store('foto');
        $temp = new Produk();
        $temp->nama = $request->nama;
        $temp->harga = $request->harga;
        $temp->id_umkm = $request->idumkm;
        $temp->kategori = $request->kategori;
        $temp->deskripsi = $request->deskripsi;

        $url = config('app.url');
        $temp->foto = $url . "/storage/app/" . $foto;


        $saved = $temp->save();


        if (!$saved) {
            return response()
                ->json([
                    'success' => false,
                    'data' => "Error"
                ]);
        } else {

            return response()
                ->json([
                    'success' => true,
                    'data' => "sukses"
                ]);
        }
    }

    public function get_produk_umkm($id)
    {
        $produk = DB::select("SELECT * FROM produk WHERE id_umkm =  '$id'");
        return response()->json($produk);
    }

    public function detail_produk($id)
    {
        return response()->json(Produk::where("id", $id)->first());
    }

    public function get_kelurahan($id)
    {
        $data = DB::table("ms_kelurahan")->where('id_kecamatan', $id)->get();
        return response()->json($data);
    }
}
