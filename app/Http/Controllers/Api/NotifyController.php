<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/10/10
 * Time: 9:23
 */

namespace App\Http\Controllers\Api;

use App\Models\Ord\OrdOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifyController extends InitController
{
    public function index(Request $request){

        $options = file_get_contents('php://input');
        $options = (array)simplexml_load_string($options, 'SimpleXMLElement', LIBXML_NOCDATA);

        $orderInfo = OrdOrder::where('serial',$options['out_trade_no'])->first();

        if($orderInfo){
            $orderInfo->status = 5;
            $orderInfo->payed_at = date('Y-m-d H:i:s');
            $orderInfo->save();
        }

        exit('success');
    }
}
