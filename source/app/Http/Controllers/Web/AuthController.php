<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cookie,Session};

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
      $token = $request->cookie('token_device');
      if ($token) {
        try {
          JWTAuth::setToken($token)->invalidate();
        } catch (JWTException) {
        }
      }
      $request->attributes->remove('user_details');
      $request->attributes->remove('token_device');
      $request->attributes->remove('user_id');
      Session::flush();
      return redirect('/')->withCookie(Cookie::forget('token_device'))->withCookie(Cookie::forget('user_id'));
    }
}
