<?php

namespace App\Models;

use App\Http\Traits\MiniProgramTrait;
use App\Http\Traits\ModelQueryExtend;
use App\Models\Ord\OrdOrder;
use App\Models\User\UserMessage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{

    use HasRoles,Notifiable,ModelQueryExtend;
    use MiniProgramTrait;

    const USER_STATUS_OPEN = 1;
    const USER_STATUS_STOP = 0;
    const USER_STATUS_DELETE = 9;

    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_TENANT = 2;
    const USER_TYPE_STAFF = 4;
    const USER_TYPE_MEMBER = 8;

    protected $appends = ['change_code'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nickname', 'email', 'password','mobile','openid','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 获取用户订单
     */
    public function order(){
        return $this->hasMany(OrdOrder::class,'user_id');
    }

    /**
     * 获取用户订单
     */
    public function message(){
        return $this->hasMany(UserMessage::class,'user_id');
    }

    /**
     * 员工太阳码
     */
    public function getChangeCodeAttribute()
    {
        try{
            $key = 'member_code_mini_id_'.$this->id;
            $appid = env('MALL_APPID');

            $scene = 'uid='.$this->id;
            $url = env('APP_API_URL').$this->getMiniCode($scene, 'pages/index/main',430,$appid);

            return $url;
        }catch(\Exception $ex){
            info(__CLASS__ . ' | ' . __FUNCTION__ . ' | ' . $ex->getFile() . ' | ' . $ex->getLine() . ' | error = ' . $ex->getMessage());
        }
    }

    /**
     * 校区
     */
    public function school(){
        return $this->belongsTo(School::class,'schoole_id');
    }
}
