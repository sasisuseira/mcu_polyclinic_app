<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Cookie,Validator};
use App\Models\{User};
use App\Helpers\{ResponseHelper,GlobalHelper};
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
   public function login(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::error_validation(__('auth.eds_required_data'),['errors' => $validator->errors()]);
            }
            $loginField = filter_var($req->username,FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $credentials = [
                $loginField => $req->username,
                'password' => $req->password,
            ];

            $existingUser = User::where($loginField, $req->username)->first();

            if ($existingUser && $existingUser->isLoginLocked()) {
                return ResponseHelper::error_validation(
                    __('auth.eds_account_locked', ['menit' => $existingUser->loginLockRemainingMinutes()])
                );
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                if ($existingUser) {
                    $locked = $existingUser->recordLoginFailure();
                    if ($locked) {
                        return ResponseHelper::error_validation(
                            __('auth.eds_account_locked', ['menit' => $existingUser->loginLockRemainingMinutes()])
                        );
                    }
                    $sisa = (int) $existingUser->login_max_attempts - (int) $existingUser->login_attempts;
                    return ResponseHelper::data_not_found(
                        __('auth.eds_invalid_credentials_attempt', ['sisa' => max($sisa, 0)])
                    );
                }
                return ResponseHelper::data_not_found(__('auth.eds_invalid_credentials'));
            }
            $user = JWTAuth::user()->load('pegawai');
            $user->resetLoginAttempts();
            if (!$user->pegawai ||$user->pegawai->status_pegawai === 'Tidak Aktif') {
                JWTAuth::invalidate($token);
                return ResponseHelper::error_validation(
                    'Akun pegawai Tidak Aktif. Silahkan hubungi administrator jika ingin membuka akses pengguna ini.'
                );
            }
            $dynamicAttributes = [
                'user_information' => $user,
                'token_akses' => $token,
            ];
            $cookie_jwt = Cookie::make('token_device', $token, env('COOKIE_TIME_EXPIRE'), env('COOKIE_PATH'), env('COOKIE_DOMAIN_ALLOWED'), env('COOKIE_IS_SECURE'), env('COOKIE_IS_HTTP_ONLY'));
            $cookie_user_id = Cookie::make('user_id', $user->id, env('COOKIE_TIME_EXPIRE'), env('COOKIE_PATH'), env('COOKIE_DOMAIN_ALLOWED'), env('COOKIE_IS_SECURE'), env('COOKIE_IS_HTTP_ONLY'));
            return ResponseHelper::success(__('auth.eds_login_successful'), $dynamicAttributes)->withCookie($cookie_jwt)->withCookie($cookie_user_id);
        } catch (\Throwable $th) {
            return ResponseHelper::error($th);
        }
    }
}
