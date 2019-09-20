<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/19
 * Time: 21:00
 */

namespace App\Http\Controllers\Api;

use App\Models\Gds\GdsGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Resources\Gds\GdsGood as GdsGoodRescource;

class MainController extends InitController
{
    public function __construct(

    )
    {

    }
    public function index(Request $request){

        return GdsGoodRescource::collection(GdsGood::where([
            'is_hot' => 1
        ])->get());
    }
}