<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/7
 * Time: 10:59
 */

namespace App\Models\Gds;

use App\Models\Comment;
use App\Models\Model;
use App\Models\Ord\OrdOrder;
use App\Models\System\SysCategory;
use App\Models\User;

class GdsGood extends Model
{
    protected $appends = [];

    public function category(){
        return $this->belongsTo(SysCategory::class,'category_id');
    }

    public function comment(){
        return $this->morphMany(Comment::class, 'model');
    }

    public function zan()
    {
        return $this->morphMany(GdsZan::class, 'model');
    }
    public function view()
    {
        return $this->morphMany(GdsView::class, 'gds_views');
    }
    public function viewer()
    {
        return $this->morphToMany(User::class, 'gds_views');
    }
    public function collect()
    {
        return $this->morphMany(GdsCollect::class, 'model');
    }
}
