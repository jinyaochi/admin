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
            'name' => $this->name ?? 0,
            'intro' => $this->intro ?? 0,
            'images' => $this->images ?? 0,
            'images2' => $this->images2 ?? 0,
            'time_at' => $this->time_at ?? 0,
            'full_address' => $this->full_address ?? 0,
            'location' => $this->location ?? 0,
            'lat' => $this->lat ?? 0,
            'lng' => $this->lng ?? 0,
        ];
    }
}
