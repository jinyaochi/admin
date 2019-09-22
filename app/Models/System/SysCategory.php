<?php

namespace App\Models\System;

use App\Models\Collection;
use App\Models\Gds\GdsCollect;
use App\Models\Gds\GdsGood;
use App\Models\Gds\GdsView;
use App\Models\Gds\GdsZan;
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

    public function collect(){
        return $this->hasManyThrough(GdsCollect::class, GdsGood::class,'category_id','model_id')->where('model_type',GdsGood::class);
    }
    public function zan(){
        return $this->hasManyThrough(GdsZan::class, GdsGood::class,'category_id','model_id')->where('model_type',GdsGood::class);
    }
    public function view(){
        return $this->hasManyThrough(GdsView::class, GdsGood::class,'category_id','model_id')->where('model_type',GdsGood::class);
    }
}
