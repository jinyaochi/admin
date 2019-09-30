<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/6
 * Time: 7:52
 */

namespace App\Http\Controllers\Admin\Member\Manage;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\InitController;
use Excel;

class UserController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.member.manage.user.';
    }

    public function index(Request $request){

        $name = $request->name ?? '';
        $schoolid = $request->school ?? '';
        $workerid = $request->worker ?? '';

        $adminSchoolId = \Auth::user()->schoole_id ?? 0;

        $lists = User::whereRaw('type & '.User::USER_TYPE_MEMBER)->where(function ($query)use($name){
            $name && $query->where('id',$name)->orWhere('mobile',$name);
        })->where(function ($query)use($schoolid,$workerid){
            $schoolid && $query->where('schoole_id',$schoolid);
            $workerid && $query->where('member_id',$workerid);
        })->whereIn('status',[User::USER_STATUS_OPEN,User::USER_STATUS_STOP])->orderBy('id','DESC');
        $adminSchoolId && $lists = $lists->whereHas('member',function ($query)use($adminSchoolId){
            $query->where('schoole_id',$adminSchoolId);
        });
        $lists = $lists->paginate(self::PAGESIZE);

        if($request->excel){
            $lists = User::select('nickname','email','created_at','integral','avatar','type')->where('type',User::USER_TYPE_MEMBER)->where(function ($query)use($name){
                $name && $query->where('email',$name)->orWhere('nickname','like',"%{$name}%");
            })->whereIn('status',[User::USER_STATUS_OPEN,User::USER_STATUS_STOP])->orderBy('id','DESC')->get();

            self::export($lists);
        }else{
            //校区
            $school = School::all();
            $worker = $schoolid ? User::where([
                'status' => User::USER_STATUS_OPEN,
                'schoole_id' => $schoolid
            ])->whereRaw('type & '.User::USER_TYPE_STAFF)->get():[];
            return view( $this->template. __FUNCTION__,compact('lists','school','worker'));
        }
    }

    public function operate(Request $request,$operate = null,User $model = null){

        if($operate == 'close'){
            $model->status = User::USER_STATUS_STOP;
            $model->save();
        }else if($operate == 'open'){
            $model->status = User::USER_STATUS_OPEN;
            $model->save();
        }else if($operate == 'remove'){
            $model->status = User::USER_STATUS_DELETE;
            $model->save();
        }else{
            return $this->error('无效请求');
        }
        return $this->success('操作成功');
    }

    public static function export($cellData){
        ini_set('memory_limit','500M');
        set_time_limit(0);//设置超时限制为0分钟

        Excel::create('下载',function($excel) use ($cellData){
            $excel->sheet('detail', function($sheet) use ($cellData){
                $sheet->rows($cellData->toArray());
            });
        })->export('xls');
        die;
    }
}
