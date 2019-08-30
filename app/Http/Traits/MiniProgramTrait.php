<?php
/**
 * Created by PhpStorm.
 * User: caojianhui
 * Date: 2019/4/28
 * Time: 1:30 PM
 */

namespace App\Http\Traits;

use App\Models\User;
use App\Models\Wechat\WxUser;
use Doctrine\Common\Cache\FilesystemCache;
use EasyWeChat\Factory;
use hisorange\BrowserDetect\Exceptions\Exception;
use Omnipay\Omnipay;

trait MiniProgramTrait
{
    /**
     * @param bool $isPayment
     * @return \EasyWeChat\MiniProgram\Application|\EasyWeChat\Payment\Application
     */
    public function miniInit($isPayment = false,$appid = null)
    {
        $program = $this->_getConfig($appid);
        $config = config('wechat.mini_program.' . $program);
        $app = Factory::miniProgram($config);
        if($isPayment){
            $config = config('wechat.payment.default');
            $app = Factory::payment($config);
        }

        return $app;
    }

    /**
     * @param $request
     * @return mixed
     * 获取用户小程序openID
     */
    public function miniProgramOpenid()
    {
        $openid =request()->header('openid')?request()->header('openid'):(request()->filled('openid')?request()
            ->openid:'');
        return $openid;
    }

    /**
     * @param null $openid
     * @param string $appid
     * @return string
     * 获取微信用户数据
     */
    public function getMiniWxusers($openid = null)
    {
        $openid = empty($openid)? $this->miniProgramOpenid():$openid;
        $appid = request()->header('appid', env('MALL_APPID'));
        $model = !empty($openid) ? WxUser::where('appid', $appid)->where('openid', $openid)->first() : '';
        return $model;
    }

    /**
     * @param $sessionKey
     * @param $encryptedData
     * @param $iv
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\DecryptException
     * 获取用户授权信息
     */
    public function getUserInfo($sessionKey, $encryptedData, $iv,$appid=null)
    {
        $decryptedData = $this->miniInit(false,$appid)->encryptor->decryptData($sessionKey, $iv, $encryptedData);
        return $decryptedData;
    }

    /**
     * @param $data
     * @param $request
     * @return int
     * 存储用户信息
     */
    public function saveMiniWxuser($data){

        $appid = $data['watermark']['appid'];
        $model = WxUser::where('openid', $data['openId'])->where('appid', $appid)
            ->first();
        if(empty($model)){
            $info = [
                'appid' => $appid,
                'openid' =>  $data['openId'],
                'nickname' => $data['nickName']??'',
                'sex' => $data['gender']??'',
                'unionid'=>$data['unionId']??'',
                'language' => $data['language'] ?? '',
                'city' => $data['city'] ?? '',
                'province' => $data['province'] ?? '',
                'country' => $data['country'] ?? '',
                'headimgurl' => $data['avatarUrl'] ?? '',
                'type'=>WxUser::TYPE_MINI,
            ];
        }else{
            $info =[
                'id'=>$model->id,
                'unionid'=>$model->unionid,
            ];
            isset($data['nickName'])?$info['nickname']= $data['nickName']:'';
            isset($data['avatarUrl'])?$info['headimgurl']= $data['avatarUrl']:'';
        }

        !empty($model) ? $info['id'] = $model->id : '';
        !empty($model)?$info['unionid'] = $model->unionid:'';

        if(!empty($info['unionid'])){
            $wx = WxUser::where('unionid',$info['unionid'])->where('user_id','>',0)->first();
            if(!empty($user = $this->userInfo()) && empty($wx)){
                $info['user_id'] = $user->id;
            }else{
                if(!empty($wx)){
                    $info['user_id'] = $wx->user_id;
                }
            }
            $model = WxUser::saveBy($info);
        }
        return $model;
    }

    /**
     * @param $data
     * @return int
     * 保存导购小程序授权信息
     */
    public function saveMiniGuideWxuser($data){

        $appid = $data['watermark']['appid'];
        $model = WxUser::where('openid', $data['openId'])->where('appid', $appid)->first();
        if(empty($model)){
            $info = [
                'appid' => $appid,
                'openid' =>  $data['openId'],
                'nickname' => $data['nickName']??'',
                'sex' => $data['gender']??'',
                'unionid'=>$data['unionId']??'',
                'language' => $data['language'] ?? '',
                'city' => $data['city'] ?? '',
                'province' => $data['province'] ?? '',
                'country' => $data['country'] ?? '',
                'headimgurl' => $data['avatarUrl'] ?? '',
                'user_id'=>$user->id??'',
                'type'=>WxUser::TYPE_MINI,
            ];
        }else{
            $info =[
                'id'=>$model->id,
                'user_id'=>$data['user_id'],
                'unionid'=>$model->unionid,
            ];
            isset($data['nickName'])?$info['nickname']= $data['nickName']:'';
            isset($data['avatarUrl'])?$info['headimgurl']= $data['avatarUrl']:'';
        }
        $model = WxUser::saveBy($info);
        return $model;
    }
    /**
     * @param $data
     * @return int
     * @param $request
     * 注册并返回用户信息
     */
    public function getUserModel($data)
    {
        $model = User::where('mobile', $data['phoneNumber'])->first();
        if(empty($model)){
            $info['nickname'] = $data['nickname']??('cw'.$data['phoneNumber']);
            $info['avatar'] = $data['avatarUrl']??'';
            $info['password'] = 'cw100_'.\Hash::make('123456');
            $info['type'] = User::USER_TYPE_MEMBER;
            $info['mobile'] = $data['phoneNumber'];
            $model = User::saveBy($info);
        }
        if(!empty($data['openId'])){
            $wxuser = $this->saveMiniWxuser($data);
            if(!empty($wxuser)){
                WxUser::where('unionid',$wxuser->unionid)->where('user_id',0)
                    ->update(['user_id'=>$model->id]);
            }
        }
        return $model;
    }


    /**
     * @param $scene
     * @param $path
     * @param int $width
     * @return bool|int
     * @throws Exception
     * 获取小程序太阳码
     */
    private function getMiniCode($scene, $path, $width = 430,$appid = null)
    {
        try{
            $filePath = get_upload_base_path('mini_'.$appid);
            $response = $this->miniInit(false,$appid)->app_code
                ->getUnlimit($scene, ['page' => $path, 'width' => $width]);
            if($response instanceof \EasyWeChat\Kernel\Http\StreamResponse){
                return get_upload_url($filePath).$response->save($filePath);
            }
            logger('获取小程序太阳码，微信返回结果$response=',collect($response)->toArray());
            throw new Exception('获取小程序码失败'.$response['errmsg']);
        }catch(\Exception $ex){
            throw new Exception($ex->getMessage());
        }
    }

    /**
     * 读取小程序配置
     */
    private function _getConfig($appid = null){
        $appid = $appid ?? request()->header('appid', env('MALL_APPID'));
        $configs = [
            env('CUSTOMER_MINI_PROGRAM_APPID', 'wxe1a70406e1eaac22') => 'customer',
            env('ADMIN_MINI_PROGRAM_APPID', 'wx99859113eb0c22d1')=>'admin',
            config('wechat.mini_program.guide_ceshi.app_id') => 'guide_ceshi',
            config('wechat.mini_program.guide.app_id') => 'guide',
            config('wechat.mini_program.default.app_id') => 'default',
            config('wechat.mini_program.mall.app_id')=>'mall',
        ];
        return $configs[$appid];
    }
}