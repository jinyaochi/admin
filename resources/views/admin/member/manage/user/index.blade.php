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
                <a href="{{ url('member/manage/user') }}"><li class="selected">用户列表</li></a>
                <a class="btn btn_r" href="{{ url('member/manage/user') }}?excel=1">+ 导出</a>
            </ul>
            <div class="mainbox">
                <div class="form-horizontal goods_nav_search clearfix">
                    <form method="get" name="search">
                        <div class="fl ml10 mr20 pos_rel">
                            <div class="ml10 mr20 fl">
                                <input type="text" name="name" placeholder="ID/手机号" class="form-control w260" value="{{request('name')}}">
                            </div>
                            <div class="ml10 mr20 fl">
                                <select id="schools" name="school">
                                    <option value="">选择校区</option>
                                    @foreach($school as $s)
                                        <option @if($s['id'] == request('school')) selected @endif value="{{$s['id']}}">{{$s['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="ml10 mr20 fl">
                                <select id="workers" name="worker">
                                    <option value="">选择业务员</option>
                                    @foreach($worker as $s)
                                        <option @if($s['id'] == request('worker')) selected @endif value="{{$s['id']}}">{{$s['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="c-datepicker-date-editor J-datepicker-range-day">
                                <input placeholder="注册时间开始日期" name="start" class="c-datepicker-data-input only-date" value="{{request('start')}}" readonly>
                                <span class="c-datepicker-range-separator">-</span>
                                <input placeholder="注册时间结束日期" name="end" class="c-datepicker-data-input only-date" value="{{request('end')}}" readonly>
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
                                <th  style="width: 8%">ID</th>
                                <th  style="width: 12%">状态</th>
                                <th  style="width: 10%">手机号</th>
                                <th  style="width: 15%">昵称</th>
                                <th  style="width: 15%">注册时间</th>
                                <th  style="width: 15%">校区</th>
                                <th  style="width: 15%">业务员</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lists as $lv)
                                <tr>
                                    <td>{{$lv['id'] ?? ' -- '}}</td>
                                    <td>{{$lv['status'] == 1 ? '正常' : '禁用'}}</td>
                                    <td>{{$lv['mobile'] ?? ' -- '}}</td>
                                    <td>{{$lv['nickname'] ?? ' -- '}}</td>
                                    <td>{{$lv['created_at'] ?? ' -- '}}</td>
                                    <td>{{$lv->member->school->name ?? ' -- '}}</td>
                                    <td>{{$lv->member->name ?? ' -- '}}</td>
                                    <td>
                                        @if($lv['status'] != \App\Models\User::USER_STATUS_STOP)
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
            app.load('core/date');
            app.load('school/manage/index/index');
        });

    </script>
@stop
