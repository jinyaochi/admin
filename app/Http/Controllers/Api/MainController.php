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
    /**
     * @param Request $request
     * @return mixed
     *
     * 首页视频
     */
    public function index(Request $request){

        return GdsGoodRescource::collection(GdsGood::where([
            'is_hot' => 1
        ])->get());
    }

    /**
     * @return mixed
     * 分类列表
     */
    public function category(){

        return SysCategoryRescource::collection(SysCategory::take(3)->get());
    }

    /**
     * @param Request $request
     * @param GdsGood|null $model
     * @return array
     * 视频详情
     */
    public function goods(Request $request,GdsGood $model = null){

        //增加浏览量
        return [];
    }
}