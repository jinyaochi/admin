<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/7
 * Time: 20:41
 */

namespace App\Models;

use App\Models\Gds\GdsZan;
use Illuminate\Database\Eloquent\Builder;

class Appoint extends Model
{
    public function school(){
        return $this->belongsTo(School::class,'school_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
