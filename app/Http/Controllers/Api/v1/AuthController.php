<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPEmail;

class AuthController extends Controller
{
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

    public function registrasi(Request $request)
    {
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

            $proses = User::create($data);

            $message = 'Berhasil melakukan registrasi';
            $token   = $proses->createToken('API Token')->plainTextToken;

            return $this->responSukses($message, $token);
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $attr       = Validator::make($request->all(), [
                'nisn'      => 'required',
                'password'  => 'required'
            ]);

            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }
    
            $credentials = request(['nisn','password']);
            if (!Auth::attempt($credentials)) {
                $message = 'Kredensial tidak cocok';
                return $this->responGagal($message, 401);
            }
    
            $message    = 'Berhasil login';
            $token      = auth()->user()->createToken('API Token')->plainTextToken;
    
            return $this->responSukses($message, $token);
            
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $logout     = $request->user()->tokens()->delete();
            if($logout != 0 || $logout != null){
                $message= 'Berhasil logout';
                return $this->responSukses($message);
            }else{
                $message= 'Harus login terlebih dahulu';
                return $this->responGagal($message, 402);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function lupaPassword(Request $request)
    {
        try {
            $attr       = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
    
            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }
    
            $cek_email = User::where('email', $request->email)->count();
    
            $otp = rand(123456,999999);
            if ($cek_email != 0) {
                $data = [
                    'email'     => $request->email,
                    'otp'       => Hash::make($otp),
                    'expire_at' => Carbon::now()->addMinutes(10),
                    'status'    => 0,
                ];

                $cek_row = DB::table('password_resets')->where('email', $request->email)->count();
                if ($cek_row == 0) {
                    $proses = DB::table('password_resets')->insert($data);
                } else {
                    $proses = DB::table('password_resets')->where('email', $request->email)->update($data);
                }

                Mail::to($request->email)->send(new OTPEmail($otp, 'Permintaan Reset Password'));
                $message= 'Berhasil mengirimkan permintaan reset password. Cek email Anda';
                return $this->responSukses($message);
            } else {
                return $this->responGagal('email tidak terdaftar', 409);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function cekOTP(Request $request)
    {
        try {
            $attr       = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp'   => 'required'
            ]);
    
            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $cek_reset_pw = DB::table('password_resets')
                            ->where(['email' => $request->email])
                            ->get();
            if (count($cek_reset_pw) == 0) {
                $message = 'Tidak ada permintaan reset password';
                return $this->responGagal($message, 400);
            }
            if (Hash::check($request->otp, $cek_reset_pw[0]->otp)) {
                if (Carbon::now() <= $cek_reset_pw[0]->expire_at) {
                    $upd_pw_reset = DB::table('password_resets')->where('email', $request->email)->update(['status' => 1]);
                    $message= 'Berhasil verifikasi OTP';
                    return $this->responSukses($message, $request->email);
                } else {
                    return $this->responGagal('Kode OTP telah kadaluarsa', 409);
                }
            } else {
                $message = 'Kode OTP salah';
                return $this->responGagal($message, 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $attr       = Validator::make($request->all(), [
                'email'     => 'required|email',
                'password'  => 'required|confirmed'
            ]);
    
            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $cek_expired  = DB::table('password_resets')
                            ->where(['email' => $request->email])
                            ->get();

            if (count($cek_expired) != 0) {
                if ($cek_expired[0]->status == 0) {
                    return $this->responGagal('Kode OTP belum terverifikasi', 409);
                }
                if (Carbon::now() <= $cek_expired[0]->expire_at) {
                    $proses       = User::where('email', $request->email)
                                        ->update(['password' => Hash::make($request->password)]);
    
                    $del_pw_reset = DB::table('password_resets')->where('email', $request->email)->delete();
    
                    $message= 'Berhasil reset password';
                    return $this->responSukses($message);
                } else {
                    return $this->responGagal('Kode OTP telah kadaluarsa', 409);
                }
            } else {
                $message = 'Tidak ada permintaan reset password';
                return $this->responGagal($message, 400);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }

    public function cekNoHp(Request $request)
    {
        try {
            $attr       = Validator::make($request->all(), [
                'no_hp'     => 'required',
            ]);
    
            if ($attr->fails()){
                $message  = $attr->errors();
                return $this->responGagal($message, 400);
            }

            $cek_no_hp  = User::where(['no_hp' => $request->no_hp])->count();

            if($cek_no_hp != 0) {
                return $this->responSukses('Nomor HP terverifikasi', $request->no_hp);
            } else {
                return $this->responGagal('Nomor HP tidak terdaftar', 409);
            }
        } catch (\Exception $e) {
            $message  = $e->getMessage();
            return $this->responGagal($message, 500);
        }
    }
}
