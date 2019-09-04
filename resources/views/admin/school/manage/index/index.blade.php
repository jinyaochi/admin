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
                <a href="{{ url('school/manage/index') }}"><li class="selected">校区列表</li></a>
            </ul>
            <div class="mainbox">
                <div class="form-horizontal goods_nav_search clearfix">
                    <form method="get" name="search">
                        <div class="fl ml10 mr20 pos_rel">
                            <input type="text" name="name" placeholder="昵称/手机号" class="form-control w260" value="{{request('name')}}">
                        </div>
                        <input type="submit" value="搜索" class="fl btn ml10 js_submit">
                    </form>
                    <a class="btn btn_r" href="{{ url('school/manage/index/create') }}">+ 添加校区</a>
                </div>
                <!--tab 切换1 bengin-->
                <div class="form-horizontal goods_nav_search clearfix">
                    <!--table 列表 bengin-->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th  style="width: 8%">ID</th>
                                <th  style="width: 30%">校区名称</th>
                                <th  >地址</th>
                                <th  style="width: 10%">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lists as $lv)
                                <tr>
                                    <td>{{$lv['id'] ?? ' -- '}}</td>
                                    <td>{{$lv['status'] == 1 ? '正常' : '禁用'}}</td>
                                    <td>{{$lv['nickname'] ?? ' -- '}}</td>
                                    <td>
                                        @if($lv['status'] != \App\Models\School::SCHOOL_STATUS_OPEN)
                                            <a class="do_action" data-confirm="确定要冻结吗？" data-url="{!! url('member/manage/user/close',['user'=>$lv['id']]) !!}">冻结</a>
                                        @else
                                            <a class="do_action red" data-confirm="确定要解冻吗？" data-url="{!! url('member/manage/user/open',['user'=>$lv['id']]) !!}">解冻</a>
                                        @endif
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
        });

    </script>
@stop
