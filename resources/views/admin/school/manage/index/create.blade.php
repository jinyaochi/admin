@extends('admin.layout.main')
@section('title')-创建/编辑类目@stop
@section('content')
    <div class="content_ch">
        <div class="admin_info clearfix">
            <ul class="nav_pills clearfix">
                <a href="{{ url('school/manage/index') }}"><li>校区管理</li></a>
                <li class="selected">
                    创建/编辑校区
                </li>
            </ul>
            <div class="mainbox">
                <form name="profile-form" id="profile-form" method="post" class="mtb20 base_form">
                    @if(!empty($model))
                        <input type="hidden" name="data[id]" value="{!! $model['id'] ?? '' !!}">
                    @endif
                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>校区名称：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" placeholder="1-32个字符" name="data[name]" maxlength="32" value="{{$model->name ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>校区简介：</label>
                        <div class="col-xs-4">
                            <textarea class="form-control " rows="3" name="data[intro]" placeholder="校区简介">{{$model->intro ?? ''}}</textarea>
                        </div>
                    </div>

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>营业时间：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" placeholder="1-32个字符" name="data[time_at]" maxlength="32" value="{{$model->time_at ?? ''}}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-xs-2 t_r">校区列表图：</label>
                        <div class="col-xs-9">
                            <ul class="multimage-gallery clearfix" id="photo-list">
                                <li id="image_box" class="my-upload-img">
                                    @if(!empty($model['images']))
                                        @foreach($model['images'] as $key => $item)
                                            <span class="self-add-img">
                                        <img src="{{$item}}">
                                        <input type="hidden" name="data[images][]" value="{{$item}}">
                                        <span hidden="" class="img-delete">
                                            <i class="icon-shanchu iconfont"></i>
                                        </span>
                                    </span>
                                        @endforeach
                                    @endif
                                </li>
                                <li @if(count($model['images']) >= 10) hidden @endif class="image-upload-add" data-num="10" data-box="image_box" data-item='<span class="self-add-img"><img src=""><input type="hidden" name="data[images][]" value=""><span hidden="" class="img-delete"><i class="icon-shanchu iconfont"></i></span></span>'>
                                    <a class="tra_photofile">上传图片</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r">校区图片展示：</label>
                        <div class="col-xs-9">
                            <ul class="multimage-gallery clearfix" id="photo-list">
                                <li id="image_box222" class="my-upload-img">
                                    @if(!empty($model['images2']))
                                        @foreach($model['images2'] as $key => $item)
                                            <span class="self-add-img">
                                        <img src="{{$item}}">
                                        <input type="hidden" name="data[images2][]" value="{{$item}}">
                                        <span hidden="" class="img-delete">
                                            <i class="icon-shanchu iconfont"></i>
                                        </span>
                                    </span>
                                        @endforeach
                                    @endif
                                </li>
                                <li @if(count($model['images2']) >= 10) hidden @endif class="image-upload-add" data-num="10" data-box="image_box222" data-item='<span class="self-add-img"><img src=""><input type="hidden" name="data[images2][]" value=""><span hidden="" class="img-delete"><i class="icon-shanchu iconfont"></i></span></span>'>
                                    <a class="tra_photofile">上传图片</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="text-danger">*</span> 所在地区：</label>
                        <div class="col-xs-8">
                            <select name="data[province]" id="province" data-next="city" data-href="/school/manage/index/area" class="province">
                                <option value="">--请选择--</option>
                                @if(count($area))
                                    @foreach($area as $kl=>$gv)
                                        <option @if($gv['code'] == $model['province']) selected @endif value="{{$gv['code'] ?? ''}}">
                                            {{$gv['name'] ?? ''}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <select name="data[city]" id="city" data-next="region" data-href="/school/manage/index/area" class="city">
                                <option value="">--请选择--</option>
                                @if($model['province'])
                                    @foreach($area[$model['province']]['son'] as $kl=>$gv)
                                        <option @if($gv['code'] == $model['city']) selected @endif value="{{$gv['code'] ?? ''}}">
                                            {{$gv['name'] ?? ''}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <select name="data[region]" id="region" data-href="/school/manage/index/area" class="district">
                                <option value="">--请选择--</option>
                                @if($model['city'])
                                    @foreach($area[$model['province']]['son'][$model['city']]['son'] as $kl=>$gv)
                                        <option @if($gv['code'] == $model['region']) selected @endif value="{{$gv['code'] ?? ''}}">
                                            {{$gv['name'] ?? ''}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            <p><small>所在地区将直接影响购买者在选择线下自提时的地区筛选，因此请如实认真选择全部地区级。</small></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="text-danger">*</span> 详细地址：</label>
                        <div class="col-xs-10">
                            <div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control w240 zoom" placeholder="请填写详细地址"  name="data[location]" id="poiText" value="{{$model->location ?? ''}}">
                                    <input type="text" class="form-control  latitude" name="data[lat]"   hidden value="{{$model->lat ?? ''}}">
                                    <input type="text" class="form-control  longitude" name="data[lng]" hidden  value="{{$model->lng ?? ''}}">
                                    <a class="btn" id="seach">查找</a>
                                </div>
                                <div class="col-xs-2 G-p-0">
                                    <input type="button" value="标记新地址" title="搜不到部门地址时，可点击标记新部门，拖动地图标记地址" class="btn btn-info"  id = "changeSearchType">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="text-danger">*</span> 地图：</label>
                        <div class="col-xs-6">
                            <div id="container" class="mt10 col-xs-8" style="height:350px;width:550px;">
                                <!--地图-->
                            </div>
                        </div>
                        <div id="infoDiv" class="col-xs-4" hidden="" style=""></div>
                    </div>

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>校长登录手机号：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control w240 zoom" placeholder="手机号" id="searchinput" value="{{isset($model->user) ? $model->user->mobile : ''}}">
                            <input type="hidden" id="adminid" name="admin[userid]" value="{{$model->user_id ?? ''}}">
                            <a class="btn" id="seachuser">验证账号</a><br />
                            <span class="red">该手机号必须为小程序注册账号</span>
                        </div>
                    </div>

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>校长登录密码：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" placeholder="1-32个字符" name="admin[pwd]" maxlength="32" value="{{(isset($model->user) && $model->user->password) ? '******' : ''}}">
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                    {{--<label class="col-xs-2 t_r"></label>--}}
                    {{--<div class="form-group">--}}
                    {{--<label class="col-xs-2 t_r">类目图片：</label>--}}
                    {{--<div class="col-xs-9">--}}
                    {{--<ul class="multimage-gallery clearfix" id="photo-list">--}}
                    {{--<li id="image_box" class="my-upload-img">--}}
                    {{--</li>--}}
                    {{--<li class="image-upload-add" data-num="1" data-box="image_box" data-item='<span class="self-add-img"><img src=""><input type="hidden" name="data[image]" value=""><span hidden="" class="img-delete"><i class="icon-shanchu iconfont"></i></span></span>'>--}}
                    {{--<a class="tra_photofile">上传图片</a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--<p class="fgray">最多五张，第一张为赠品主图，建议尺寸：800*800 像素</p>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}

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
