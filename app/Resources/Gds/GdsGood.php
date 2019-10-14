<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/18
 * Time: 10:54
 */

namespace App\Resources\Gds;

use App\Models\Ord\OrdOrder;
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
        $_this = $this;

        return [
            'id' => $this->id ?? 0,
            'name' => $this->name ?? '',
            'price' => $this->price ?? '',
            'category_id' => $this->category_id ?? '',
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
            'viewer' => UserRescource::collection($this->viewer()->take($request->num ?? 3)->get()),
            'hasbuy' => OrdOrder::where('user_id',\Auth::guard(config('app.guard.api'))->user()->id ?? 0)->where('status',5)->whereHas('items',function ($query)use($_this){
                $query->where('category_id',$_this['category_id']);
            })->count()
        ];
    }

}
