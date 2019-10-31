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
use Excel;

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

        $adminSchoolId = \Auth::user()->schoole_id ?? 0;



        if($request->excel){
            $lists = Appoint::where('type',1)->where(function ($query)use($schoolid,$start,$end,$adminSchoolId){
                $schoolid && $query->where('school_id',$schoolid);
                $start && $query->where('created_at','>',$start);
                $end && $query->where('created_at','<',$end);
                $adminSchoolId && $query->where('school_id',$adminSchoolId);
            })->orderBy('id','DESC')->get();

            self::export($lists);
        }else{

            $lists = Appoint::where('type',1)->where(function ($query)use($schoolid,$start,$end,$adminSchoolId){
                $schoolid && $query->where('school_id',$schoolid);
                $start && $query->where('created_at','>',$start);
                $end && $query->where('created_at','<',$end);
                $adminSchoolId && $query->where('school_id',$adminSchoolId);
            })->orderBy('id','DESC')->paginate(self::PAGESIZE);

            $school = School::all();

        }

        return view( $this->template. __FUNCTION__,compact('lists','school','adminSchoolId'));
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

    public static function export($cellData){
        ini_set('memory_limit','500M');
        set_time_limit(0);//设置超时限制为0分钟

        Excel::create('下载',function($excel) use ($cellData){
            $excel->sheet('detail', function($sheet) use ($cellData){
                $sheet->rows($cellData->map(function ($item){
                    return [
                        $item['id'] ?? '',
                        $item['mobile'] ?? '',
                        $item['name'] ?? '',
                        $item['content'] ?? '',
                        $item->school->name ?? '未分配',
                        $item->user->member->name ?? $item->user->member->mobile ?? ' -- ',
                        $item['created_at'] ?? '',
                    ];
                }));
            });
        })->export('xls');
        die;
    }
}
