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

class MessageController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.member.manage.message.';
    }

    public function index(Request $request){
        $schoolid = $request->school ?? '';
        $start = $request->start ?? '';
        $end = $request->end ?? '';
        $lists = Appoint::where('type',1)->where(function ($query)use($schoolid,$start,$end){
            $schoolid && $query->where('school_id',$schoolid);
            $start && $query->where('created_at','>',$start);
            $end && $query->where('created_at','<',$end);
        })->orderBy('id','DESC')->paginate(self::PAGESIZE);
        $school = School::all();

        return view( $this->template. __FUNCTION__,compact('lists','school'));
    }

    public function remove(Request $request,Appoint $model = null){

        $model->delete();

        return $this->success('操作成功',url('member/manage/message'));

    }

    public function change(Request $request,Appoint $model = null){

        $sid = $request->sid ?? 0;

        $model->school_id = $sid;
        $model->save();
        return $this->success('操作成功',url('member/manage/message'));

    }

}
