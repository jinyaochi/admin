<?php

namespace App\Models;

use App\Http\Traits\MiniProgramTrait;
use App\Http\Traits\ModelQueryExtend;
use App\Models\Gds\GdsGood;
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

    protected $appends = ['change_code','cover','show_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'nickname', 'email', 'password','mobile','openid','type','avatar','gender'
    ];

    public function getCoverAttribute()
    {
        return $this->avatar ?: env('APP_URL').'/static/images/my-head2.jpg';
    }

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

    public function collect(){
        return $this->belongsToMany(GdsGood::class,'gds_collects','user_id','model_id')->where('model_type',GdsGood::class)->orderBy('id','DESC');
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

    public function getShowNameAttribute()
    {
        return $this->nickname ?: substr_replace($this->mobile,'***',3,5) ?? '';
    }

    /**
     * 员工太阳码
     */
    public function getChangeCodeAttribute()
    {
        try{
            $key = 'member_code_mini_id_'.$this->id;

            if(\Cache::has($key)){
                $url = \Cache::get($key);
            }else{
                $scene = 'uid='.$this->id;
                $url = env('APP_API_URL').$this->getMiniCode($scene, 'pages/index/index');
                \Cache::forever($key,$url);
            }

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

    /**
     * 业务员
     */
    public function member(){
        return $this->belongsTo(User::class,'member_id');
    }

    /**
     * 业务员拉新数
     */
    public function myuser(){
        $start = request('start') ?? '';
        $end = request('end') ?? '';

        return $this->hasMany(User::class,'member_id')->where(function ($query)use($start,$end){
            $start && $query->where('created_at','>',$start);
            $end && $query->where('created_at','<',$end);
        });
    }
}
