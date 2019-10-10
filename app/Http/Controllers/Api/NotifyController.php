<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/10/10
 * Time: 9:23
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifyController extends InitController
{
    public function index(Request $request){

        $options = file_get_contents('php://input');
        $options = (array)simplexml_load_string($options, 'SimpleXMLElement', LIBXML_NOCDATA);
        info($options);
        exit('success');
    }
}
