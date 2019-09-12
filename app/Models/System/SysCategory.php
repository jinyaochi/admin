<?php

namespace App\Models\System;

use App\Models\Collection;
use App\Models\Gds\GdsGood;
use App\Models\Model;

class SysCategory extends Model
{
    const TYPE_PRODUCT = 1;

    const STATUS_OK = 1;
    const STATUS_NO = 2;

    /**
     * @param int $gurad
     * @return Collection
     *
     * 获取列表
     */
    public static function getCategorys($type,$status = false)
    {
        $where = ['type'=>$type];

        $status && $where['status'] = $status;

        $collection = new Collection(SysCategory::where($where)->get());

        return $collection->buildTree('parent_id', 'node');
    }

    public function goods(){
        return $this->hasMany(GdsGood::class,'category_id');
    }
}
