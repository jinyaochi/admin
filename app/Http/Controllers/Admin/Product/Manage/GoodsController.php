<?php

namespace App\Http\Controllers\Admin\Product\Manage;


use App\Http\Controllers\Admin\InitController;
use App\Models\Gds\GdsGood;
use App\Models\System\SysCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoodsController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.product.manage.goods.';
    }

    public function delete(GdsGood $model = null){

        $model->delete();
        return $this->success('操作成功');
    }

    public function index(Request $request){
        $name = $request->name ?? '';
        $lists = GdsGood::where(function ($query) use ($name){
            $name && $query->where('name',$name)->orWhere('teacher',$name);
        })->where('is_hot',0)->orderBy('sorts','DESC')->paginate(self::PAGESIZE);
        return view( $this->template. __FUNCTION__,compact('lists'));
    }

    public function create(Request $request,GdsGood $model = null){
        if($request->isMethod('get')) {
            $categories = SysCategory::getCategorys(SysCategory::TYPE_PRODUCT,SysCategory::STATUS_OK)->mergeTree('node');
            return view($this->template . __FUNCTION__, compact('model','categories'));
        }

        $data = $request->data;

        $rules = [
            'name' => 'required|unique:gds_goods,name,'.($model['id'] ?? 'NULL').',id',
            'category_id' => 'required',
            'price' => 'required',
            'pay' => 'required',
            'image' => 'required',
            'url' => 'required',
        ];
        $messages = [
            'name.required' => '请输入名称',
            'name.unique' => '名称已存在',
            'category_id.required' => '请选择分类',
            'price.required' => '请输入价格',
            'pay.required' => '请选择支付方式',
            'image.required' => '请选择封面',
            'url.required' => '请选择视频',
        ];

        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        try {
            $data['intro'] || $data['intro'] = '';
            $data['sorts'] || $data['sorts'] = 0;
            $data['price'] && $data['price'] = $data['price']*100;
            GdsGood::saveBy($data);
            return $this->success('操作成功',url('product/manage/goods'));
        }catch (\Exception $e) {
            return $this->error('操作异常，请联系开发人员'.$e->getMessage());
        }
    }
}
