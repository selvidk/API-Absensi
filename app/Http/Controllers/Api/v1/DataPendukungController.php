<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DataPendukungController extends Controller
{
    public function __construct() {
        $this->user       = auth('sanctum')->user();
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

    public function getKelas(Request $request)
    {
        try {
            if ($request->has('jurusan') && $request->has('kelas')) {
                $data  = DB::table('kelas')->join('jurusan', 'jurusan.id', '=', 'kelas.id_jurusan')
                            ->select('kelas.id as id_kelas', 'kelas.id_jurusan', 'jurusan', 'kelas')
                            ->where('jurusan', 'LIKE', '%'.$request->jurusan.'%')
                            ->where('kelas', 'LIKE', '%'.$request->kelas.'%')
                            ->get();
            } elseif ($request->has('jurusan')) {
                $data  = DB::table('kelas')->join('jurusan', 'jurusan.id', '=', 'kelas.id_jurusan')
                            ->select('kelas.id as id_kelas', 'kelas.id_jurusan', 'jurusan', 'kelas')
                            ->where('jurusan', 'LIKE', '%'.$request->jurusan.'%')
                            ->get();
            } elseif ($request->has('kelas')) {
                $data  = DB::table('kelas')->join('jurusan', 'jurusan.id', '=', 'kelas.id_jurusan')
                            ->select('kelas.id as id_kelas', 'kelas.id_jurusan', 'jurusan', 'kelas')
                            ->where('kelas', 'LIKE', '%'.$request->kelas.'%')
                            ->get();
            } else {
                $data  = DB::table('kelas')->join('jurusan', 'jurusan.id', '=', 'kelas.id_jurusan')
                            ->select('kelas.id as id_kelas', 'kelas.id_jurusan', 'jurusan', 'kelas')
                            ->get();
            }
        
            $message = 'Berhasil mendapatkan data kelas dan jurusan';

            return $this->responSukses($message, $data);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getMapel(Request $request)
    {
        try {
            if ($this->user->id_kelas != null) {
                $jurusan = DB::table('kelas')->where('id', $this->user->id_kelas)->first();

                if ($jurusan != null) {
                    if ($request->has('mapel')) {
                        $data = DB::table('mapel')->where('id_jurusan', $jurusan->id_jurusan)
                                ->where('mapel', 'LIKE', '%'.$request->mapel.'%')
                                ->get();
                    } elseif ($request->has('id_mapel')) {
                        $data = DB::table('mapel')->where('id_jurusan', $jurusan->id_jurusan)
                                ->where('id', $request->id_mapel)
                                ->get();
                    } else {
                        $data = DB::table('mapel')->where('id_jurusan', $jurusan->id_jurusan)->get();
                    }

                    $message = 'Berhasil mendapatkan data mapel';
                    return $this->responSukses($message, $data);
                } else {
                    return $this->responGagal('Kelas tidak terdaftar', 400);
                }
            } else {
                return $this->responGagal('Kelas tidak terdaftar', 400);
            }
            
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function prosesAbsensi(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'id_mapel'   => 'required',
                'keterangan' => 'required',
            ]);

            if ($attr->fails()) {
                $message  = "Data yang dimasukkan salah";
                return $this->responGagal($message, 400);
            }

            if ($this->user->id_kelas != null) {
                $jurusan = DB::table('kelas')->where('id', $this->user->id_kelas)->first();

                if ($jurusan != null) {
                    $semester = $this->semester($jurusan->kelas, null);
                    $data  = [
                        'id_users'   => $this->user->id,
                        'id_mapel'   => $request->id_mapel, 
                        'keterangan' => $request->keterangan,
                        'semester'   => $semester,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $proses  = DB::table('kehadiran_siswa')->insert($data);

                    $message = 'Berhasil melakukan absensi';
                    return $this->responSukses($message, $data);
                } else {
                    return $this->responGagal('Kelas tidak terdaftar', 400);
                }
            } else {
                return $this->responGagal('Kelas tidak terdaftar', 400);
            }
            
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getAbsensi()
    {
        try {
            if ($this->user->id_kelas != null) {
                $jurusan = DB::table('kelas')->where('id', $this->user->id_kelas)->first();

                if ($jurusan != null) {
                    $semester = $this->semester($jurusan->kelas, null);
                    $data     = DB::table('kehadiran_siswa')->where('id_users', $this->user->id)
                                ->where('semester', $semester)
                                ->get();

                    $message = 'Berhasil mendapatkan data absensi';
                    return $this->responSukses($message, $data);
                } else {
                    return $this->responGagal('Kelas tidak terdaftar', 400);
                }
            } else {
                return $this->responGagal('Kelas tidak terdaftar', 400);
            }
            
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function countAbsensi(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'semester' => 'in:ganjil,genap',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            if ($this->user->id_kelas != null) {
                $jurusan = DB::table('kelas')->where('id', $this->user->id_kelas)->first();

                if ($jurusan != null) {
                    $request->has('semester') ? $semester = $request->semester : $semester = null;
                    $semester = $this->semester($jurusan->kelas, $semester);
                    $masuk    = DB::table('kehadiran_siswa')
                                ->where([
                                    'id_users'   => $this->user->id,
                                    'semester'   => $semester,
                                    'keterangan' => 'masuk',
                                ])
                                ->count();
                    $sakit    = DB::table('kehadiran_siswa')
                                ->where([
                                    'id_users'   => $this->user->id,
                                    'semester'   => $semester,
                                    'keterangan' => 'sakit',
                                ])
                                ->count();
                    $izin    = DB::table('kehadiran_siswa')
                                ->where([
                                    'id_users'   => $this->user->id,
                                    'semester'   => $semester,
                                    'keterangan' => 'izin',
                                ])
                                ->count();
                    $data = [
                        'id_users'    => $this->user->id,
                        'semester'    => $semester,
                        'total_masuk' => $masuk,
                        'total_sakkit'=> $sakit,
                        'total_izin'  => $izin,
                    ];

                    $message = 'Berhasil mendapatkan data absensi';
                    return $this->responSukses($message, $data);
                } else {
                    return $this->responGagal('Kelas tidak terdaftar', 400);
                }
            } else {
                return $this->responGagal('Kelas tidak terdaftar', 400);
            }
            
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function semester($kelas, $semester)
    {
        $cek_kelas = explode(" ", $kelas)[0];

        if ($cek_kelas == 'X') {
            $list_smt = [1,2];
        } elseif ($cek_kelas == 'XI') {
            $list_smt = [3,4];
        } else {
            $list_smt = [5,6];
        }

        if($semester == null) {
            $year         = date('Y');
            $cek_semester = $year % 2;
            $cek_semester == 0 ? $semester = 'genap' : $semester = 'ganjil';
        }

        $semester == 'ganjil' ? $semester = $list_smt[0] : $semester = $list_smt[1];

        return $semester;
    }

    public function getHasilBelajar(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'semester' => 'required|in:ganjil,genap',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $kelas     = DB::table('kelas')->where('id', $this->user->id_kelas)->get();

            if (count($kelas) != 0) {
                $smt_angka = $this->semester($kelas[0]->kelas, $request->semester);

                $hasil_bljr= DB::table('hasil_belajar')
                                ->where(['id_users' => $this->user->id, 'semester' => $smt_angka])
                                ->first();

                $kelas     = DB::table('kelas')->join('jurusan', 'jurusan.id', '=', 'kelas.id_jurusan')
                                ->where('kelas.id', $this->user->id_kelas)
                                ->first();

                $data      = [
                    'id'            => $hasil_bljr->id,
                    'nama_lengkap'  => $this->user->nama_lengkap,
                    'nis'           => $this->user->nis,
                    'nisn'          => $this->user->nisn,
                    'kelas'         => $kelas->kelas,
                    'jurusan'       => $kelas->jurusan,
                    'semester'      => $hasil_bljr->semester,
                    'tahun_ajaran'  => $hasil_bljr->tahun_ajaran,
                    'sikap'         => $hasil_bljr->sikap,
                    'catatan_wali'  => $hasil_bljr->catatan_wali,
                    'tanggapan_ortu'=> $hasil_bljr->tanggapan_ortu,
                    'wali_kelas'    => $hasil_bljr->wali_kelas,
                    'nip'           => $hasil_bljr->nip,
                    'created_at'    => $hasil_bljr->created_at,
                ];

                $message   = 'Berhasil mendapatkan hasil belajar semester '.$request->semester.' ('.$smt_angka.')';
                return $this->responSukses($message, $data);
            } else {
                return $this->responGagal('Kelas tidak ada dalam daftar', 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getNilai(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'semester' => 'required|in:ganjil,genap',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $kelas     = DB::table('kelas')->where('id', $this->user->id_kelas)->get();

            if (count($kelas) != 0) {
                $smt_angka = $this->semester($kelas[0]->kelas, $request->semester);

                $data      = DB::table('nilai_siswa')
                                ->leftJoin('mapel', 'mapel.id', '=', 'nilai_siswa.id_mapel')
                                ->where(['id_users' => $this->user->id, 'semester' => $smt_angka])
                                ->select('nilai_siswa.id', 'mapel', 'semester', 'pengetahuan_kb', 'pengetahuan_angka', 'keterampilan_kb', 'keterampilan_angka')
                                ->get();

                $message = 'Berhasil mendapatkan nilai semester '.$request->semester.' ('.$smt_angka.')';
                return $this->responSukses($message, $data);
            } else {
                return $this->responGagal('Kelas tidak ada adalam daftar', 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getPkl(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'semester' => 'required|in:ganjil,genap',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $kelas     = DB::table('kelas')->where('id', $this->user->id_kelas)->get();

            if (count($kelas) != 0) {
                $smt_angka = $this->semester($kelas[0]->kelas, $request->semester);

                $data      = DB::table('pkl_siswa')
                                ->where(['id_users' => $this->user->id, 'semester' => $smt_angka])
                                ->get();

                $message = 'Berhasil mendapatkan pkl semester '.$request->semester.' ('.$smt_angka.')';
                return $this->responSukses($message, $data);
            } else {
                return $this->responGagal('Kelas tidak ada adalam daftar', 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getEkskul(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'semester' => 'required|in:ganjil,genap',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $kelas     = DB::table('kelas')->where('id', $this->user->id_kelas)->get();

            if (count($kelas) != 0) {
                $smt_angka = $this->semester($kelas[0]->kelas, $request->semester);

                $data      = DB::table('ekskul_siswa')
                                ->where(['id_users' => $this->user->id, 'semester' => $smt_angka])
                                ->get();

                $message = 'Berhasil mendapatkan ekstrakurikuler semester '.$request->semester.' ('.$smt_angka.')';
                return $this->responSukses($message, $data);
            } else {
                return $this->responGagal('Kelas tidak ada adalam daftar', 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function getPrestasi(Request $request)
    {
        try {
            $attr = Validator::make($request->all(), [
                'semester' => 'required|in:ganjil,genap',
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $kelas     = DB::table('kelas')->where('id', $this->user->id_kelas)->get();

            if (count($kelas) != 0) {
                $smt_angka = $this->semester($kelas[0]->kelas, $request->semester);

                $data      = DB::table('prestasi_siswa')
                                ->where(['id_users' => $this->user->id, 'semester' => $smt_angka])
                                ->get();

                $message = 'Berhasil mendapatkan prestasi semester '.$request->semester.' ('.$smt_angka.')';
                return $this->responSukses($message, $data);
            } else {
                return $this->responGagal('Kelas tidak ada adalam daftar', 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }
}
