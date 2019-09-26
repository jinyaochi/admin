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
            'name' => $this->user->show_name ?? 0,
            'cover' => $this->user->cover ?? 0,
            'created_at' => explode(' ',(string)$this->created_at)[0],
            'content' => $this->content ?? ' -- ',
            'zan' => $this->zan()->count(),
            'selfzan' => $this->zan()->where([
                'user_id' => \Auth::guard(config('app.guard.api'))->user()->id ?? 0
            ])->count(),
            'reply_name' => $this->reply->user->show_name ?? '',
            'son' => Comment::collection($this->son)
        ];
    }
}
