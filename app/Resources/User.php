<?php

namespace App\Resources;

class User extends Base
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
            'name' => $this->name ?? 0,
            'cover' => $this->cover ?? '',
            'nickname' => $this->show_name ?? '',
            'mobile' => $this->mobile ?? '',
        ];
    }
}
