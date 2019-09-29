@extends('admin.layout.main')
@section('title')
-Index
@stop
@section('content')

    <div class="content_ch">
        <!--warp bengin-->
        <!--内容区 bengin-->
        <div class="admin_info clearfix">
            <!--right bengin-->
            <ul class="nav_pills mb10 clearfix">
                <a href="{{ url('product/manage/category') }}"><li class="selected">评论列表</li></a>
            </ul>
            <div class="mainbox">
                <div class="form-horizontal goods_nav_search clearfix">
                    <form method="get" name="search">
                        <div class="fl ml10 mr20 pos_rel">
                            <div class="ml10 mr20 fl">
                                <input type="text" name="name" placeholder="用户名/手机号/评论内容" class="form-control w260" value="{{request('name')}}">
                            </div>
                            <div class="c-datepicker-date-editor J-datepicker-range-day">
                                <input placeholder="评论时间开始日期" name="start" class="c-datepicker-data-input only-date" value="{{request('start')}}" readonly>
                                <span class="c-datepicker-range-separator">-</span>
                                <input placeholder="评论时间结束日期" name="end" class="c-datepicker-data-input only-date" value="{{request('end')}}" readonly>
                            </div>
                        </div>
                        <input type="submit" value="搜索" class="fl btn ml10 js_submit">
                    </form>
                </div>
                <!--tab 切换1 bengin-->
                <div class="form-horizontal goods_nav_search clearfix">
                    <!--table 列表 bengin-->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th  style="width: 15%">用户名</th>
                                <th  style="width: 15%">手机号</th>
                                <th  style="width: 15%">视频/校区名称</th>
                                <th  >评论内容</th>
                                <th  style="width: 15%">发布时间</th>
                                <th  style="width: 15%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lists as $lv)
                                <tr>
                                    <td>{{$lv->user->nickname ?? '--'}}</td>
                                    <td>{{$lv->user->mobile ?? '--'}}</td>
                                    <td>{{$lv->model->name ?? ''}}</td>
                                    <td>{{$lv->content ?? ''}}</td>
                                    <td>{{$lv->created_at ?? ''}}</td>
                                    <td>
                                        <a class="do_action" data-confirm="确定要删除吗？" data-url="{!! url('product/manage/comment/delete',['id'=>$lv['id']]) !!}">删除</a>
                                        <a class="do_action" data-confirm="确定要操作吗？" data-url="{!! url('product/manage/comment/delete',['id'=>$lv['id']]) !!}">删除并封评</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3">暂时没有任何数据</td> </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--tab 切换1 end-->
            </div>
            <!--right end-->
        </div>
        <!--内容区 end-->
    </div>

@stop
@section('script')
<script>
    var __seajs_debug = 1;
    seajs.use("/admin/js/app.js", function (app) {
        app.bootstrap();
        app.load('core/date');
    });

</script>
@stop
