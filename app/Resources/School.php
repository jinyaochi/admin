<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/9/23
 * Time: 15:01
 */

namespace App\Resources;


class School extends Base
{

    public function toArray($request)
    {
        return [
            'id' => $this->id ?? 0,
        ];
    }
}
