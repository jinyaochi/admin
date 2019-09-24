<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Resources\User as UserRescource;
use App\Rules\Mobile;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Illuminate\Support\Facades\Validator;

class LoginController extends InitController{

    /**
     * @param Request $request
     * 电话账号登陆
     */
    public function mobile(Request $request){
        $data = [
            'code' => $request->code ?? '',
            'mobile' => $request->mobile ?? '',
        ];

        $rules = [
            'code' => 'required',
            'mobile' => ['required', New Mobile()],
        ];
        $messages = [
            'code.required' => '请输入验证码',
            'mobile.required' => '请填写正确手机号',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        if(!$log = User\UserCode::where([
            'mobile' => $data['mobile'],
            'code' => $data['code'],
            'status' => 1,
        ])->first()){
            return $this->error('验证码错误');
        }

        //登陆（注册）
        $user = User::firstOrCreate(['mobile' => $data['mobile']], [
            'type' => User::USER_TYPE_MEMBER
        ]);
        $token = \JWTAuth::fromUser($user);

        return $this->respondWithToken($token);
    }

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function wx(Request $request)
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

        $data = [
            'code' => rand(100000,999999),
            'mobile' => $request->mobile ?? '',
            'status' => 1
        ];

        $rules = [
            'code' => 'required',
            'mobile' => ['required', New Mobile()],
        ];
        $messages = [
            'code.required' => 'code为空',
            'mobile.required' => '请填写正确手机号',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        if($log = User\UserCode::where([
            'mobile' => $data['mobile'],
            ['created_at','>',date('Y-m-d H:i:s',time() - 60)]
        ])->first()){
            return $this->error('一分钟内只能发送一次');
        }

        AlibabaCloud::accessKeyClient(env('ALI_ID'), env('ALI_SECRET'))
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $data['mobile'],
                        'SignName' => "TinyUse微用",
                        'TemplateCode' => "SMS_68070321",
                        'TemplateParam' => '{"code":"'.$data['code'].'"}',
                    ],
                ])->request();
            if($result->toArray()['Code'] == 'OK'){
                //记录发送记录
                User\UserCode::where([
                    'mobile' => $data['mobile']
                ])->update([
                    'status' => 9
                ]);

                User\UserCode::saveBy($data);

                return $this->success('ok');

            }

            return $this->error('error');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

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
