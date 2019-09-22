<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/19
 * Time: 21:00
 */

namespace App\Http\Controllers\Api;

use App\Models\Gds\GdsGood;
use App\Models\System\SysCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Resources\Gds\GdsGood as GdsGoodRescource;
use App\Resources\System\SysCategory as SysCategoryRescource;

class MainController extends InitController
{
    public function index(Request $request){

        return GdsGoodRescource::collection(GdsGood::where([
            'is_hot' => 1
        ])->get());
    }

    public function category(){

        return SysCategoryRescource::collection(SysCategory::all());
    }
}