<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Helpers\Helper;
use App\Services\Captcha;

class AuthController extends Controller
{

    public function login(Request $req, Captcha $captcha)
    {
        $this->validate($req, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if(! $captcha->check($req->captcha_id, $req->captcha)){
            return Helper::responseJson(401, "Captcha tidak valid");
        }

        $credentials = $req->only(['username', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return Helper::responseJson(401, "Unauthorized");
        }

        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::user(),
            'expires_in' => Auth::factory()->getTTL() * 60 * 24
        ];
        return Helper::responseJson(200, "ok", $data);
    }

    public function user(){
        $user = Auth::user();
        if(!$user){
            return Helper::responseJson(404, "Data user tidak ditemukan");
        }
        return Helper::responseJson(200, "ok", $user);
    }

    public function captcha(Captcha $captcha)
    {
        $captcha->destroy();
        return Helper::responseJson(200, "ok", $captcha->generate());
    }

    public function logout()
    {
        Auth::logout();
        return Helper::responseJson(200, "ok");
    }
}
