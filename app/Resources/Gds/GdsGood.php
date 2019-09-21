<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/18
 * Time: 10:54
 */

namespace App\Resources\Gds;

use App\Resources\Base;

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
            'video' => explode('?',$this->url)[0],
            'intro' => $this->intro ?? '暂无简介',
            'cover' => $this->image ?? '',
            'collect' => $this->collect()->count() ?? '',
            'zan' => $this->zan()->count() ?? '',
            'view' => $this->view()->count() ?? '',
            'viewer' => $this->view()->take(3),
        ];
    }

}