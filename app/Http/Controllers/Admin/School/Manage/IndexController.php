<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/6
 * Time: 7:52
 */

namespace App\Http\Controllers\Admin\School\Manage;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\InitController;
use Excel;

class IndexController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.school.manage.index.';
    }

    public function index(Request $request){

        $name = $request->name ?? '';

        $adminSchoolId = \Auth::user()->schoole_id ?? 0;

        $lists = School::where(function ($query)use($name,$adminSchoolId){
            $name && $query->where('name','like',"%{$name}%");
            $adminSchoolId && $query->where('id',$adminSchoolId);
        })->orderBy('id','DESC')->paginate(self::PAGESIZE);

        return view( $this->template. __FUNCTION__,compact('lists'));

    }

    public function create(Request $request,School $model = null){

        if($request->isMethod('get')) {
            $area = json_decode(config('regin')['area'],true);

            return view($this->template . __FUNCTION__, compact('model','area'));
        }

        $data = $request->data;

        $rules = [
            'name' => 'required',
            'intro' => 'required',
            'time_at' => 'required',
            'province' => 'required',
            'city' => 'required',
            'region' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
        $messages = [
            'name.required' => '请填写校区名称',
            'intro.required' => '请填写简介',
            'time_at.required' => '请填写营业时间',
            'province.required' => '请选择省',
            'city.required' => '请选择市',
            'region.required' => '请选择区',
            'location.required' => '请填写详细地址',
            'lat.required' => '缺少经纬度',
            'lng.required' => '缺少经纬度',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        $admin = $request->admin;

        $rules = [
            'userid' => 'required',
            'pwd' => 'required',
        ];
        $messages = [
            'userid.required' => '请验证手机号',
            'pwd.required' => '请填写密码',
        ];

        $validator = Validator::make($admin, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        if(!$admin['userid']){
            return $this->error('请填写正确验证的手机号');
        }


        try {

            //管理员逻辑
            $adminInfo = User::where('id','!=',env('SUPER_ID'))->find($admin['userid']);

            if($adminInfo){

                if(!($adminInfo['type'] & User::USER_TYPE_TENANT)){
                    $adminInfo->type += User::USER_TYPE_TENANT;
                }

                if(!($adminInfo['type'] & User::USER_TYPE_STAFF)){
                    $adminInfo->type += User::USER_TYPE_STAFF;
                }

                $admin['pwd'] != '******' && $adminInfo->password = \Hash::make($admin['pwd']);
                $adminInfo->schoole_id = $model['id'];
                $adminInfo->save();

                $adminInfo->assignRole(['school']);
            }

            $data['user_id'] = $admin['userid'];

            //保存校区
            School::saveBy($data);

            return $this->success('操作成功',url('school/manage/index'));
        }catch (\Exception $e) {
            return $this->error('操作异常，请联系开发人员'.$e->getMessage());
        }
    }

    public function area(Request $request){

        $province = $request->province ?? '';
        $city = $request->city ?? '';

        $area = json_decode(config('regin.area'),true);

        if($city){
            return $area[$province]['son'][$city]['son'];
        }else{
            return $area[$province]['son'];
        }

    }

    public function change($type,School $model = null){
        if($type == 'close'){
            $model->status = School::SCHOOL_STATUS_STOP;
            $model->save();
        }else if($type == 'open'){
            $model->status = School::SCHOOL_STATUS_OPEN;
            $model->save();
        }else{
            $model->delete();
        }
        return $this->success('操作成功');

    }

    public function search(Request $request){
        $mobile = $request->mobile ?? '';

        $type = $request->type ?? '';

        if(!$mobile){
            return $this->error('手机号为空', null, true);
        }

        $user = User::where('mobile',$mobile)->first();

        if(!$user){
            return $this->error('当前手机号未注册', null, true);
        }

        if($type == 'worker'){
            //验证是否已经是业务员
            if($user['type'] & User::USER_TYPE_STAFF){
                return $this->error('已经是业务员了', null, true);
            }
        }

        return $this->success('操作成功',null,$user);

    }
}
