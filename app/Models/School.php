<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/9/4
 * Time: 13:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{

    use SoftDeletes;

    protected $casts = ['images' => 'array','images2' => 'array'];
    protected $appends = ['full_address'];

    const SCHOOL_STATUS_OPEN = 1;
    const SCHOOL_STATUS_STOP = 2;
    const SCHOOL_STATUS_DELETE = 9;

    /**
     * @return string
     * 获取所在地信息
     */
    public function getFullAddressAttribute()
    {
        $area = json_decode(config('regin.area'),true);
        return $area[$this->province]['name'] . ' ' .$area[$this->province]['son'][$this->city]['name'] . ' ' .$area[$this->province]['son'][$this->city]['son'][$this->region]['name'];
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function comment()
    {
        return $this->morphMany(Comment::class, 'model');
    }

}
