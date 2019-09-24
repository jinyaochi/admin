<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Resources\User as UserRescource;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends InitController{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function login(Request $request)
    {
        $appid = env('MALL_APPID'); //填写微信小程序appid  正式服
        $secret = env('MALL_SECRET'); //填写微信小程序secret

        $code = $request->code ?? '';

        $wJson = json_decode(file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code"),true);

        if(!isset($wJson['openid'])){
            return $this->error('openid error', 404);
        }

        $user = User::firstOrCreate(['openid' => $wJson['openid']], [
            'type' => User::USER_TYPE_MEMBER
        ]);
        $token = \JWTAuth::fromUser($user);

        return $this->respondWithToken($token,$wJson['openid'],$wJson);
    }

    /**
     * @param Request $request
     * 发送短信
     */
    public function code(Request $request){

    }


    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userinfo()
    {
        $user = $this->guard()->user();
        if(!$user){
            return $this->error('no login');
        }
        return new UserRescource($user);
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->success('Successfully logged out');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$openid = null,$extra = [])
    {
        return $this->success('success',null,[
            'access_token' => $token,
            'openid' => $openid,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'extra' => $extra
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard(config('app.guard.api'));
    }
}
