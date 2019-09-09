<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/9/5
 * Time: 16:08
 */

namespace App\Http\Controllers\Admin\School\Manage;

use App\Http\Controllers\Admin\InitController;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkerController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.school.manage.worker.';
    }

    public function workerlist(Request $request){
        $schoolid = $request->schoolid ?? '';

        $lists = User::where(function ($query)use($schoolid){
            $query->where('schoole_id',$schoolid);
            $query->where('type','&',User::USER_TYPE_STAFF);
        })->orderBy('id','DESC')->get();

        return $this->success('success', null, $lists);

    }

    public function index(Request $request,School $model = null){

        $name = $request->name ?? '';
        $lists = User::where(function ($query)use($name,$model){
            $query->where('schoole_id',$model->id);
            $query->where('type','&',User::USER_TYPE_STAFF);
            $name && $query->where('name','like',"%{$name}%");
        })->orderBy('id','DESC')->paginate(self::PAGESIZE);

        return view( $this->template. __FUNCTION__,compact('lists','model'));

    }

    public function create(Request $request,School $model = null){

        if($request->isMethod('get')) {
            $area = json_decode(config('regin')['area'],true);

            return view($this->template . __FUNCTION__, compact('model','area'));
        }

        $data = $request->data;

        $rules = [
            'name' => 'required',
            'userid' => 'required',
        ];
        $messages = [
            'name.required' => '请输入姓名',
            'userid.required' => '请验证手机号',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        try {

            $user = User::find($data['userid']);

            if(!($user['type'] & User::USER_TYPE_STAFF)){
                $user->type += User::USER_TYPE_STAFF;
            }

            $user->schoole_id = $model['id'];
            $user->name = $data['name'];
            $user->save();

            return $this->success('操作成功',url('school/manage/worker/'.$model['id']));
        }catch (\Exception $e) {
            return $this->error('操作异常，请联系开发人员'.$e->getMessage());
        }
    }

}
