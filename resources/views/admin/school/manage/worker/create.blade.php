@extends('admin.layout.main')
@section('title')-创建/编辑类目@stop
@section('content')
    <div class="content_ch">
        <div class="admin_info clearfix">
            <ul class="nav_pills clearfix">
                <a href="{{ url('school/manage/index') }}"><li>校区管理</li></a>
                <a href="{{ url('school/manage/worker/'.$model['id']) }}"><li>业务员列表</li></a>
                <li class="selected">
                    添加业务员
                </li>
            </ul>
            <div class="mainbox">
                <form name="profile-form" id="profile-form" method="post" class="mtb20 base_form">
                    @if(!empty($model))
                        <input type="hidden" name="data[id]" value="{!! $model['id'] ?? '' !!}">
                    @endif

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>业务员姓名：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" placeholder="1-32个字符" name="data[name]" maxlength="32" value="">
                        </div>
                    </div>

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>业务员小程序账号：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control w240 zoom" placeholder="手机号" id="searchinput" value="">
                            <input type="hidden" id="adminid" name="data[userid]" value="{{$model->user_id ?? 0}}">
                            <a class="btn" id="seachuser2">验证账号</a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r">&nbsp;</label>
                        <div class="col-xs-8">
                            <input type="submit" class="btn" value="保存">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('script')
    <script src="https://map.qq.com/api/js?v=2.exp&key=ECUBZ-FRJW3-HD43M-YPU3P-LOIW5-SPFUT"></script>
    <script>
        var __seajs_debug = 1;
        seajs.use("/admin/js/app.js", function (app) {
            app.bootstrap();
            app.load('core/upload');
            app.load('core/map');
            app.load('school/manage/index/index');
        });

    </script>
@stop
