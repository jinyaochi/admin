<?php

namespace App\Models\System;

use App\Models\Collection;
use App\Models\Gds\GdsCollect;
use App\Models\Gds\GdsGood;
use App\Models\Gds\GdsView;
use App\Models\Gds\GdsZan;
use App\Models\Model;
use App\Models\Ord\OrdOrder;
use Illuminate\Database\Eloquent\Builder;

class SysCategory extends Model
{
    const TYPE_PRODUCT = 1;

    const STATUS_OK = 1;
    const STATUS_NO = 2;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sorts', function(Builder $builder) {
            $builder->orderBy('sorts', 'desc');
        });
    }

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

    public function order(){
        return $this->belongsToMany(OrdOrder::class,'ord_order_items','category_id','order_id');
    }

    public function goods(){
        return $this->hasMany(GdsGood::class,'category_id')->orderBy('sorts','DESC');
    }

    public function collect(){
        return $this->hasManyThrough(GdsCollect::class, GdsGood::class,'category_id','model_id')->where('model_type',GdsGood::class);
    }
    public function zan(){
        return $this->hasManyThrough(GdsZan::class, GdsGood::class,'category_id','model_id')->where('model_type',GdsGood::class);
    }
    public function view(){
        return $this->hasManyThrough(GdsView::class, GdsGood::class,'category_id','gds_views_id')->where('gds_views_type',GdsGood::class);
    }
}
