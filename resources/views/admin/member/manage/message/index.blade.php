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
                <a><li class="selected">用户消息</li></a>
            </ul>
            <div class="mainbox">
                <div class="form-horizontal goods_nav_search clearfix">
                    <form method="get" name="search">
                        <div class="fl ml10 mr20 pos_rel">
                            <div class="ml10 mr20 fl">
                                <select id="schools" name="school">
                                    <option value="">选择校区</option>
                                    @foreach($school as $s)
                                        <option @if($s['id'] == request('school')) selected @endif value="{{$s['id']}}">{{$s['name']}}</option>
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
                                <th style="width: 8%">ID</th>
                                <th style="width: 15%">电话</th>
                                <th style="width: 15%">姓名</th>
                                <th style="width: 15%">年级</th>
                                <th style="width: 15%">所属校区</th>
                                <th style="width: 15%">申请时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($lists as $lv)
                                <tr>
                                    <td>{{$lv['id'] ?? ' -- '}}</td>
                                    <td>{{$lv['mobile'] ?? ' -- '}}</td>
                                    <td>{{$lv['name'] ?? ' -- '}}</td>
                                    <td>{{$lv['content'] ?? ' -- '}}</td>
                                    <td>{{$lv->school->name ?? '未分配'}}</td>
                                    <td>{{$lv['created_at'] ?? ' -- '}}</td>
                                    <td>
                                        <a class="red" style="position: relative; display: inline-block; width: 50px; text-align: center;">
                                            分配
                                            <ul style=" line-height: 30px; position: absolute; left: -200px; top: 0; width: 199px; background: #eeeeee;">
                                                @foreach($school as $s)
                                                    <li class="do_action" data-confirm="确定分配到{{$s['name']}}？" data-url="{!! url('member/manage/message/change',['id'=>$lv['id']]) !!}?sid={{$s['id']}}">
                                                        {{$s['name']}}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </a>
                                        <a class="do_action" data-confirm="确定要删除吗？" data-url="{!! url('member/manage/message/remove',['id'=>$lv['id']]) !!}">删除</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">暂时没有任何数据</td> </tr>
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
    <style>
        .red ul{
            display: none;
        }
        .red:hover ul{
            display: block;
        }
    </style>
    <script>
        var __seajs_debug = 1;
        seajs.use("/admin/js/app.js", function (app) {
            app.bootstrap();
            app.load('core/date');
        });

    </script>
@stop
