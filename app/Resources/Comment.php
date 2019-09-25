<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/26
 * Time: 6:54
 */

namespace App\Resources;

class Comment extends Base
{
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? 0,
            'content' => $this->content ?? ' -- ',
            'zan' => $this->zan()->count(),
            'selfzan' => $this->zan()->where([
                'user_id' => \Auth::user()->id ?? 0
            ])->count(),
        ];
    }
}
