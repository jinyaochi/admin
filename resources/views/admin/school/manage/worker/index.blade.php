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
                <a href="{{ url('school/manage/index') }}"><li>校区管理</li></a>
                <a href="{{ url('school/manage/worker/'.$model['id']) }}"><li class="selected">业务员列表</li></a>
            </ul>
            <div class="mainbox">
                <div class="form-horizontal goods_nav_search clearfix">
                    <form method="get" name="search">
                        <div class="fl ml10 mr20 pos_rel">
                            <div class="fl ml10 mr20 pos_rel">
                                <input type="text" name="name" placeholder="姓名/手机号" class="form-control w260" value="{{request('name')}}">
                            </div>
                            <div class="c-datepicker-date-editor J-datepicker-range-day">
                                <input placeholder="拉新排名开始日期" name="start" class="c-datepicker-data-input only-date" value="{{request('start')}}" readonly>
                                <span class="c-datepicker-range-separator">-</span>
                                <input placeholder="拉新排名结束日期" name="end" class="c-datepicker-data-input only-date" value="{{request('end')}}" readonly>
                            </div>
                        </div>
                        <input type="submit" value="搜索" class="fl btn ml10 js_submit">
                    </form>
                    <a class="btn btn_r" href="{{ url('school/manage/worker/'.$model['id'].'/create') }}">+ 添加业务员</a>
                </div>
                <!--tab 切换1 bengin-->
                <div class="form-horizontal goods_nav_search clearfix">
                    <!--table 列表 bengin-->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th  style="width: 8%">ID</th>
                                <th  style="width: 5%">姓名</th>
                                <th  style="width: 10%">手机号</th>
                                <th  style="width: 12%">校区</th>
                                <th  style="width: 15%">拉新数</th>
                                <th  style="width: 15%">二维码</th>
                                <th  style="width: 10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lists as $lv)
                                <tr>
                                    <td>{{$lv['id'] ?? ' -- '}}</td>
                                    <td>{{$lv['name'] ?? ''}}</td>
                                    <td>{{$lv['mobile'] ?? ' -- '}}</td>
                                    <td>{{$lv->school->name ?? ''}}</td>
                                    <td>{{$lv->myuser()->count() ?? '0'}}</td>
                                    <td><img style="width: 100px;" src="{{$lv->change_code}}" /></td>
                                    <td>
                                        <a class="do_action" data-confirm="确定要删除吗？" data-url="{!! url('member/manage/user/remove',['user'=>$lv['id']]) !!}">删除</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8">暂时没有任何数据</td> </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--tab 切换1 end-->
                @if(!$lists->isEmpty())
                    {!! $lists->appends(request()->all())->render() !!}
                @endif
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
