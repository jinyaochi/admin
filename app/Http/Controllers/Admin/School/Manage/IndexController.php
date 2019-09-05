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
        $lists = School::where('status',School::SCHOOL_STATUS_OPEN)->orderBy('id','DESC')->paginate(self::PAGESIZE);

        return view( $this->template. __FUNCTION__,compact('lists'));

    }

    public function create(Request $request,School $model = null){

        if($request->isMethod('get')) {
            $area = json_decode(config('regin')['area'],true);

            return view($this->template . __FUNCTION__, compact('model','area'));
        }

        $data = $request->data;

        $rules = [
            'type' => 'required',
            'name' => 'required|unique:gds_goods,name,'.($model['id'] ?? 'NULL').',id',
            'category_id' => 'required',
            'teacher' => 'required',
            'timer' => 'required',
            'price' => 'required',
            'pay' => 'required',
        ];
        $messages = [
            'type.required' => '请选择分类',
            'name.required' => '请输入名称',
            'name.unique' => '名称已存在',
            'category_id.required' => '请选择分类',
            'timer.required' => '请填写视频时长',
            'price.required' => '请输入价格',
            'pay.required' => '请选择支付方式',
        ];

        if($model){
            unset($rules['category_id']);
            unset($rules['type']);
        }

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        try {
            $data['intro'] || $data['intro'] = '';
            $data['timer'] || $data['timer'] = 100;
            $data['sorts'] || $data['sorts'] = 0;
            GdsGood::saveBy($data);
            return $this->success('操作成功',url('product/manage/goods'));
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
}
