<?php
/**
 * Created by PhpStorm.
 * User: 89340
 * Date: 2019/4/7
 * Time: 20:30
 */

namespace App\Http\Controllers\Admin\Product\Manage;

use App\Http\Controllers\Admin\InitController;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CommentController extends InitController
{
    public function __construct()
    {
        $this->template = 'admin.product.manage.comment.';
    }

    public function index(Request $request){
        $name = $request->name ?? '';
        $start = $request->start ?? '';
        $end = $request->end ?? '';

        $lists = Comment::where([
            'parent_id' => 0
        ])->where(function ($query)use($name){
            $query->whereHas('user',function ($query)use($name){
                $query->where('nickname','like',"%{$name}%")->orWhere('mobile',$name);
            })->orWhere('content','like',"%{$name}%");
        })->where(function ($query)use($start,$end){
            $start && $query->where('created_at','>',$start);
            $end && $query->where('created_at','<',$end);
        })->paginate(self::PAGESIZE);
        return view( $this->template. __FUNCTION__,compact('lists'));
    }
    public function delete(Comment $model = null){
        $model->son()->delete();
        $model->delete();
        return $this->success('操作成功');
    }
}
