<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/6
 * Time: 21:53
 */

namespace App\Http\Controllers\Admin\Member\Manage;

use App\Models\Appoint;
use App\Models\School;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\InitController;

class CallbackController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.member.manage.callback.';
    }

    public function index(Request $request){

        $start = $request->start ?? '';
        $end = $request->end ?? '';

        $lists = Appoint::where('type',2)->where(function ($query)use($start,$end){
            $start && $query->where('created_at','>',$start);
            $end && $query->where('created_at','<',$end);
        })->orderBy('id','DESC')->paginate(self::PAGESIZE);

        return view( $this->template. __FUNCTION__,compact('lists'));
    }

    public function remove(Request $request,Appoint $model = null){

        $model->delete();

        return $this->success('操作成功',url('member/manage/message'));

    }

}
