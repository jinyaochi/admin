<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/18
 * Time: 10:54
 */

namespace App\Resources\Gds;

use App\Resources\Base;
use App\Resources\User as UserRescource;

class GdsGood extends Base
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? 0,
            'name' => $this->name ?? '',
            'price' => $this->price ?? '',
            'pay' => ($this->pay == 2 || $this->price==0) ? '免费' : '付费',
            'video' => explode('?',$this->url)[0],
            'intro' => $this->intro ?? '暂无简介',
            'cover' => $this->image ?? '',
            'collect' => $this->collect()->count() ?? '',
            'selfcollect' => $this->collect()->where([
                    'user_id' => \Auth::guard(config('app.guard.api'))->user()->id ?? 0
                ])->count(),
            'zan' => $this->zan()->count() ?? '',
            'selfzan' => $this->zan()->where([
                'user_id' => \Auth::guard(config('app.guard.api'))->user()->id ?? 0
            ])->count(),
            'view' => $this->view()->count() ?? '',
            'viewer' => UserRescource::collection($this->viewer()->take(3)->get()),
        ];
    }

}
