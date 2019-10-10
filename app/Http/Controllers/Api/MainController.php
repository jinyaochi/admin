<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/19
 * Time: 21:00
 */

namespace App\Http\Controllers\Api;

use App\Models\Appoint;
use App\Models\Comment;
use App\Models\Gds\GdsGood;
use App\Models\Ord\OrdOrder;
use App\Models\Ord\OrdOrderItem;
use App\Models\School;
use App\Models\System\SysCategory;
use App\Models\User\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Resources\Gds\GdsGood as GdsGoodRescource;
use App\Resources\System\SysCategory as SysCategoryRescource;
use App\Resources\Comment as CommentRescource;
use App\Resources\School as SchoolRescource;
use Illuminate\Support\Facades\Validator;

class MainController extends InitController
{
    /**
     * @param Request $request
     * @param SysCategory|null $category
     * 下订单
     */
    public function mkorder(Request $request, SysCategory $category = null){

        $user = \Auth::user();

        $serial = time().$user['id'];
        $price = $category->goods()->where('pay',1)->sum('price');

        if(!($price > 0)){
            return $this->error('订单金额为零');
        }

        try{
            DB::beginTransaction();

            //生成订单
            $order = OrdOrder::saveBy([
                'serial' => $serial,
                'user_id' => $user['id'],
                'mobile' => $user['mobile'],
                'goods_name' => $category['name'],
                'pay_type' => 1,
                'status' => 1,
                'price' => $price,
                'name' => $user['nickname'] ?? $user['mobile'],
            ]);

            //写入购买记录
            OrdOrderItem::saveBy([
                'order_id' => $order['id'],
                'category_id' => $category['id'],
            ]);
            //请求支付参数
            $app = new \Wxpay\UnifiedOrder_pub();
            $app->setParameter('method','mbupay.wxpay.jsapi');
            $app->setParameter('body','能动视频');
            $app->setParameter('out_trade_no',$serial);
            $app->setParameter('total_fee',1);
            $app->setParameter('openid',$user['openid'] ?? 'oVem45adOxAnAbV52iAzd-fvaPmM');
            $app->setParameter('notify_url',env('APP_URL').'/notify');
            $app->setParameter('is_minipg',1);

            $res = $app->getAppParameters();

            DB::commit();
            return $this->success('下单成功',null,$res);
        } catch(\Exception $ex) {
            DB::rollback();

            info(__CLASS__ . ' | ' . __FUNCTION__ . ' | ' . $ex->getFile() . ' | ' . $ex->getLine() . ' | error = ' . $ex->getMessage());

            return $this->error('下单失败'.$ex->getMessage());
        }

    }

    /**
     * @param Request $request
     * @return mixed
     *
     * 首页视频
     */
    public function index(Request $request){

        return GdsGoodRescource::collection(GdsGood::where([
            'is_hot' => 1
        ])->get());
    }

    /**
     * 我的收藏
     */
    public function mycollect(){
        $user = \Auth::user();
        return GdsGoodRescource::collection($user->collect);
    }

    /**
     * 我的购买
     */
    public function mybuy(){
        $user = \Auth::user();
        return GdsGoodRescource::collection(GdsGood::whereHas('category',function ($query)use($user){
            $query->whereHas('order',function ($query)use($user){
                $query->where('user_id',$user->id);
                $query->where('status',5);
            });
        })->get());
    }

    /**
     * @return mixed
     * 分类列表
     */
    public function category(){

        return SysCategoryRescource::collection(SysCategory::take(3)->get());
    }

    /**
     * @param Request $request
     * @param SysCategory|null $model
     * 分类商品详情
     */
    public function categoryGoods(Request $request,SysCategory $model = null){

        return new SysCategoryRescource($model,true);
    }
    /**
     * @param Request $request
     * @param GdsGood|null $model
     * @return array
     * 视频详情
     */
    public function goods(Request $request,GdsGood $model = null){

        //增加浏览量
        $model->view()->create([
            'user_id' => \Auth::guard(config('app.guard.api'))->user()->id ?? 0
        ]);
        return new GdsGoodRescource($model);
    }

    /**
     * @param Request $request
     * 校区列表
     */
    public function school(Request $request){

        $latitude = $request->latitude ?? 0;
        $longitude = $request->longitude ?? 0;

        return SchoolRescource::collection(School::where('status',School::SCHOOL_STATUS_OPEN)->orderByRaw('abs(lat-'.$latitude.') + abs(lng-'.$longitude.')')->get());

    }

    /**
     * @param Request $request
     * @return int
     * 校区详情
     */
    public function schoolShow(Request $request,School $model = null){

        return new SchoolRescource($model);
    }

    /**
     * 校区评论
     */
    public function schoolComments(Request $request,School $model = null){

        return CommentRescource::collection($model->comment()->where('parent_id',0)->get());

    }

    /**
     * @param Request $request
     * @param School|null $model
     * @return mixed
     * 商品评论列表
     */
    public function goodsComments(Request $request,GdsGood $model = null){
        return CommentRescource::collection($model->comment()->where('parent_id',0)->get());
    }

    /**
     * @param Request $request
     * @param GdsGood|null $model
     * @return \Illuminate\Http\JsonResponse
     * 商品评论
     */
    public function goodsCommentsCreate(Request $request,GdsGood $model = null){
        $data = [
            'content' => $request->content,
        ];

        $rules = [
            'content' => 'required',
        ];
        $messages = [
            'content.required' => '请输入评论',
        ];
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }
        $user = \Auth::user();

        $model->comment()->create([
            'content' => $data['content'],
            'user_id' => $user->id,
            'parent_id' => $request->parent_id ?: 0,
            'reply_id' => $request->reply_id ?: 0,
        ]);

        return $this->success('评论成功');
    }

    /**
     * @param Request $request
     * 新增校区评论
     */
    public function schoolCommentsCreate(Request $request,School $model = null){

        $data = [
            'content' => $request->content,
        ];

        $rules = [
            'content' => 'required',
        ];
        $messages = [
            'content.required' => '请输入评论',
        ];
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }
        $user = \Auth::user();

        $model->comment()->create([
            'content' => $data['content'],
            'user_id' => $user->id,
            'parent_id' => $request->parent_id ?: 0,
            'reply_id' => $request->reply_id ?: 0,
        ]);

        return $this->success('评论成功');

    }

    /**
     * @param Request $request
     * @param GdsGood|null $model
     * @return \Illuminate\Http\JsonResponse
     * 商品收藏
     */
    public function goodsCollect(Request $request,GdsGood $model = null){
        $user = \Auth::user();

        if($has = $model->collect()->where('user_id',$user['id'])->first()){
            $has->delete();
        }else{
            $model->collect()->create([
                'user_id' => \Auth::user()->id
            ]);
        }
        return $this->success('收藏成功');
    }

    /**
     * @param Request $request
     * @param GdsGood|null $model
     * @return \Illuminate\Http\JsonResponse
     * 商品点赞
     */
    public function goodsZan(Request $request,GdsGood $model = null){
        $user = \Auth::user();

        if($has = $model->zan()->where('user_id',$user['id'])->first()){
            $has->delete();
        }else{
            $model->zan()->create([
                'user_id' => \Auth::user()->id
            ]);
        }
        return $this->success('点赞成功');
    }

    /**
     * @param Request $request
     * @param Comment|null $model
     * @return \Illuminate\Http\JsonResponse
     * 学校点赞
     */
    public function schoolCommentsZan(Request $request,Comment $model = null){
        $user = \Auth::user();

        if($has = $model->zan()->where('user_id',$user['id'])->first()){
            $has->delete();
        }else{
            $model->zan()->create([
                'user_id' => \Auth::user()->id
            ]);
        }
        return $this->success('点赞成功');
    }

    public function goodsCommentsZan(Request $request,Comment $model = null){
        $user = \Auth::user();

        if($has = $model->zan()->where('user_id',$user['id'])->first()){
            $has->delete();
        }else{
            $model->zan()->create([
                'user_id' => \Auth::user()->id
            ]);
        }
        return $this->success('点赞成功');
    }

    /**
     * @param Request $request
     * @param int $type
     * @return \Illuminate\Http\JsonResponse
     * 预约联系客服
     */
    public function appoint(Request $request,$type = 1){

        $data = [
            'name' => $request->name ?? '',
            'mobile' => $request->mobile ?? '',
            'content' => $request->content ?? '',
        ];
        $data = $data + ($type == 1 ? [
                'code' => $request->code ?? '',
            ]:[]);

        $rules = [
            'name' => 'required',
            'mobile' => 'required',
            'content' => 'required',
        ];
        $rules = $rules + ($type == 1 ? [
            'code' => 'required',
        ]:[]);

        $messages = [
            'name.required' => '请输入姓名',
            'mobile.required' => '请输入手机号',
        ];
        $messages = $messages + ($type == 1 ? [
            'content.required' => '请输入年级',
            'code.required' => '请输入验证码',
        ]:[
            'content.required' => '请输入评论',
        ]);

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), null, true);
        }

        if(isset($data['code']) && !$log = UserCode::where([
            'mobile' => $data['mobile'],
            'code' => $data['code'],
            'status' => 1,
        ])->first()){
            return $this->error('验证码错误');
        }

        Appoint::saveBy([
            'type' => $type,
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'school_id' => $request->sid ?? 0,
            'content' => $data['content'],
        ]);

        return $this->success('提交成功');
    }
}
