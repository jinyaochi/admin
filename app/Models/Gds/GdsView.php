<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/6/27
 * Time: 9:35
 */

namespace App\Models\Gds;


use App\Models\Model;

class GdsView extends Model
{
    protected $fillable = [
        'user_id'
    ];
    public function gds_views()
    {
        return $this->morphTo();
    }
}
