<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/9/4
 * Time: 13:30
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{

    use SoftDeletes;
    const SCHOOL_STATUS_OPEN = 1;
    const SCHOOL_STATUS_STOP = 2;
    const SCHOOL_STATUS_DELETE = 9;

}
