<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/7
 * Time: 18:57
 */

namespace App\Models\Ord;

use App\Models\Model;
use App\Models\System\SysCategory;

class OrdOrderItem extends Model
{
    public function category(){
        return $this->belongsTo(SysCategory::class,'category_id');
    }
}
