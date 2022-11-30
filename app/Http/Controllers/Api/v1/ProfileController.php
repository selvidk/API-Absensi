<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct() {
        $this->user      = auth('sanctum')->user();
        $this->image_path = storage_path('/app/public/profiles/');
    }

    public function responSukses($message, $data=null, int $code = 200)
    {
        return response()->json([
             'status'  => 'Sukses',
             'message' => $message,
             'data'    => $data
        ], $code);
    }

    public function responGagal($message=null, int $code = null)
    {
        return response()->json([
             'status'  => 'Gagal',
             'message' => $message,
        ], $code);
    }

    // public function imagePath($image)
    // {
    //     return storage_path('/app/public/profiles/').$image; 
    // }

    public function getProfile()
    {
        return $this->user;
        try {
            $attr = Validator::make($request->all(), [
                'nama_lengkap' => 'required|string|max:45',
                'nisn'         => 'required|max:10|unique:users,nisn',
                'email'        => 'required|string|email|unique:users,email',
                'password'     => 'required|confirmed',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $data    = [
                'nama_lengkap' => $request->nama_lengkap,
                'nisn'         => $request->nisn,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
            ];

            // $proses  = DB::table('users')->insert($data);
            $proses = User::create($data);

            $message = 'Berhasil melakukan registrasi';
            $token   = $proses->createToken('API Token')->plainTextToken;

            return $this->responSukses($message, $token);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getBiodata()
    {
        try {
            if ($this->user->id_kelas != null) {
                $jurusan = DB::table('kelas')->join('jurusan', 'jurusan.id', '=', 'kelas.id_jurusan')
                        ->where('kelas.id', $this->user->id_kelas)
                        ->select('jurusan', 'kelas')->first();
            } else {
                $jurusan = null;
            }

            $data_lain = User::leftJoin('data_siswa', 'data_siswa.id_users', '=', 'users.id')
                                ->where('users.id', $this->user->id)
                                ->select('jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'berat_badan', 'tinggi_badan', 'gol_darah', 'rencana_lulus')
                                ->first();
            

            $data      = [
                'id'             => $this->user->id,
                'email'          => $this->user->email,
                'nama_lengkap'   => $this->user->nama_lengkap,
                'nama_kecil'     => $this->user->nama_kecil,
                'nis'            => $this->user->nis,
                'nisn'           => $this->user->nisn,
                'kelas'          => $jurusan == null ? null : $jurusan->kelas,
                'jurusan'        => $jurusan == null ? null : $jurusan->jurusan,
                'no_hp'          => $this->user->no_hp,
                'image'          => $this->user->image == null ? null: $this->image_path.$this->user->image,
                'jenis_kelamin'  => $data_lain->jenis_kelamin,
                'tempat_lahir'   => $data_lain->tempat_lahir,
                'tanggal_lahir'  => $data_lain->tanggal_lahir,
                'alamat'         => $data_lain->alamat,
                'berat_badan'    => $data_lain->berat_badan,
                'tinggi_badan'   => $data_lain->tinggi_badan,
                'gol_darah'      => $data_lain->gol_darah,
                'rencana_lulus'  => $data_lain->rencana_lulus,
            ];

            $message    = 'Berhasil mendapatkan biodata';
    
            return $this->responSukses($message, $data);
            
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function updateBiodata(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'email'         => 'required|email|unique:users,email,'.$this->user->id,
                'nama_lengkap'  => 'required|string',
                'nis'           => 'required|unique:users,nis,'.$this->user->id,
                'nisn'          => 'required|unique:users,nisn,'.$this->user->id,
                'kelas'         => 'required',
                'no_hp'         => 'required|max:15|unique:users,no_hp,'.$this->user->id,
                'image'         => 'image|mimes:jpg,png,jpeg',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            if ($request->hasFile('image')) {
                $image_name  = time() . '.' . $request->file('image')->extension();
                $request->file('image')->storeAs('public/profiles', $image_name);
    
                if (!is_null($this->user->image)) {
                    $path = $this->image_path.$this->user->image;
                    unlink($path);
                }

                $data    = [
                    'email'        => $request->email,
                    'nama_lengkap' => $request->nama_lengkap,
                    'nama_kecil'   => $request->nama_kecil,
                    'nis'          => $request->nis,
                    'nisn'         => $request->nisn,
                    'id_kelas'     => $request->kelas,
                    'no_hp'        => $request->no_hp,
                    'image'        => $image_name,
                ];
            } else {
                $data    = [
                    'email'        => $request->email,
                    'nama_lengkap' => $request->nama_lengkap,
                    'nama_kecil'   => $request->nama_kecil,
                    'nis'          => $request->nis,
                    'nisn'         => $request->nisn,
                    'id_kelas'     => $request->kelas,
                    'no_hp'        => $request->no_hp,
                ];
            }

            $proses = User::where('id', $this->user->id)->update($data);
            $message = 'Berhasil memperbarui biodata utama';
            return $this->responSukses($message);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function updateDataLain(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'jenis_kelamin' => 'in:laki-laki,perempuan',
                'tanggal_lahir' => 'date',
                'gol_darah'     => 'in:a,b,o,ab',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $cek_data = DB::table('data_siswa')->where('id_users', $this->user->id)->count();

            $data    = [
                'id_users'        => $this->user->id,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'alamat'          => $request->alamat,
                'berat_badan'     => $request->berat_badan,
                'tinggi_badan'    => $request->tinggi_badan,
                'gol_darah'       => $request->gol_darah,
                'rencana_lulus'   => $request->rencana_lulus,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];

            if ($cek_data == 0) {
                $proses = DB::table('data_siswa')->insert($data);
            } else {
                $proses = DB::table('data_siswa')->where('id_users', $this->user->id)->update($data);
            }

            $message = 'Berhasil memperbarui biodata pelengkap';
            return $this->responSukses($message);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getWali()
    {
        try {
            $proses = DB::table('ortu_siswa')->where('id_users', $this->user->id)->get();

            if (count($proses) != 0) {
                $data = $proses;
            } else {
                $data = 'belum ada data orang tua atau wali';
            }

            $message    = 'Berhasil mendapatkan data orang tua dan wali';
            return $this->responSukses($message, $data);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function updateWali(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'ket_data'      => 'required|in:ayah,ibu,wali',
                'tanggal_lahir' => 'date',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $data    = [
                'id_users'        => $this->user->id,
                'ket_data'        => $request->ket_data,
                'nama'            => $request->nama,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'alamat'          => $request->alamat,
                'pendidikan'      => $request->pendidikan,
                'pekerjaan'       => $request->pekerjaan,
                'penghasilan'     => $request->penghasilan,
                'status'          => $request->status,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];

            $cek_data = DB::table('ortu_siswa')->where(['id_users' => $this->user->id, 'ket_data' => $request->ket_data])->count();

            if ($cek_data == 0) {
                $proses = DB::table('ortu_siswa')->insert($data);
            } else {
                $proses  = DB::table('ortu_siswa')->where(['id_users' => $this->user->id, 'ket_data' => $request->ket_data])->update($data);
            }
            $message = 'Berhasil memperbarui data '.$request->ket_data;
            return $this->responSukses($message);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getPendidikan()
    {
        try {
            $proses = DB::table('pendidikan_siswa')->where('id_users', $this->user->id)->get();

            if (count($proses) != 0) {
                $data = $proses;
            } else {
                $data = 'belum ada data';
            }

            $message    = 'Berhasil mendapatkan data riwayat pendidikan';
            return $this->responSukses($message, $data);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function updatePendidikan(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'tingkat_pend'  => 'required',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }


            $data    = [
                'id_users'        => $this->user->id,
                'tingkat_pend'    => $request->tingkat_pend,
                'nama_sekolah'    => $request->nama_sekolah,
                'tahun_lulus'     => $request->tahun_lulus,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ];

            $cek_data = DB::table('pendidikan_siswa')->where(['id_users' => $this->user->id, 'tingkat_pend' => $request->tingkat_pend])->count();

            if ($cek_data == 0) {
                $proses = DB::table('pendidikan_siswa')->insert($data);
            } else {
                $proses  = DB::table('pendidikan_siswa')->where(['id_users' => $this->user->id, 'tingkat_pend' => $request->tingkat_pend])->update($data);
            }
            $message = 'Berhasil memperbarui data '.$request->tingkat_pend;
            return $this->responSukses($message);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'current_password'       => 'required',
                'new_password'           => 'required',
                'password_confirmation'  => 'required|same:new_password'
            ]);

            if ($attr->fails('password_confirmation')) {
                $message  = "Konfirmasi password tidak sama";
                return $this->responGagal($message, 400);
            }

            if (Hash::check($request->current_password, $this->user->password)) {
                $data  = User::where('id', $this->user->id)->update(['password' => Hash::make($request->new_password)]);
                return $this->responSukses('Berhasil memperbarui password');
            } else {
                $message = 'Password saat ini tidak sama';
                return $this->responGagal($message, 400);
            }

        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }
}
