<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/20
 * Time: 15:15
 */

namespace App\Resources\System;

use App\Models\Ord\OrdOrder;
use App\Resources\Base;
use App\Resources\Gds\GdsGood;

class SysCategory extends Base
{

    public function __construct($resource, $hasGoods = false) {
        parent::__construct($resource);
        $this->hasGoods = $hasGoods;
    }
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
            'collect' => $this->collect()->count() ?? '',
            'view' => $this->view()->count() ?? '',
            'zan' => $this->zan()->count() ?? '',
            'goods' => $this->when($this->hasGoods,function (){
                return GdsGood::collection($this->goods);
            }),
            'price' => $this->goods()->where('pay',1)->sum('price')/100,
            'hasbuy' => OrdOrder::where('user_id',\Auth::guard(config('app.guard.api'))->user()->id ?? 0)->where('status',5)->whereHas('items',function ($query)use($_this){
                $query->where('category_id',$_this['id']);
            })->count()
        ];
    }
}
