@extends('admin.layout.main')
@section('title')-创建/编辑类目@stop
@section('content')
    <div class="content_ch">
        <div class="admin_info clearfix">
            <ul class="nav_pills clearfix">
                <a href="{{ url('product/manage/goods') }}"><li>课程管理</li></a>
                <li class="selected">
                    创建/编辑课程
                </li>
            </ul>
            <div class="mainbox">
                <form name="profile-form" id="profile-form" method="post" class="mtb20 base_form">
                    @if(!empty($model))
                        <input type="hidden" name="data[id]" value="{!! $model['id'] ?? '' !!}">
                    @endif

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="red">*</span>所属类目：</label>
                        <div class="col-xs-8">
                            <select name="data[category_id]" class="select-change-style w160" @if(!empty($model['id'])) disabled="disabled" @endif >
                                <option value="0">---请选择----</option>
                                {{--@foreach($categories as $item)--}}
                                    {{--<option value="{{$item['id']}}" @if($model['category_id'] == $item['id']) selected @endif >{{'|' . str_repeat(' -- ',$item['level'])}}{{$item['name']}}</option>--}}
                                {{--@endforeach--}}
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r">封面：</label>
                        <div class="col-xs-9">
                            <ul class="multimage-gallery clearfix" id="photo-list">
                                <li id="image_box" class="my-upload-img">
                                    @if(!empty($model['image']))
                                            <span class="self-add-img">
                            <img src="{{$model['image']}}">
                            <input type="hidden" name="data[image]" value="{{$model['image']}}">
                            <span hidden="" class="img-delete">
                                <i class="icon-shanchu iconfont"></i>
                            </span>
                        </span>
                                    @endif
                                </li>
                                <li @if(isset($model['image'])) hidden @endif class="image-upload-add" data-num="1" data-box="image_box" data-item='<span class="self-add-img"><img src=""><input type="hidden" name="data[image]" value=""><span hidden="" class="img-delete"><i class="icon-shanchu iconfont"></i></span></span>'>
                                    <a class="tra_photofile">上传图片</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group category-msg-l1">
                        <label class="col-xs-2 t_r"><span class="red">*</span>课程名称：</label>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" placeholder="1-32个字符" name="data[name]" maxlength="32" value="{{$model->name ?? ''}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r">介绍图片：</label>
                        <div class="col-xs-9">
                            <ul class="multimage-gallery clearfix" id="photo-list">
                                <li id="image_box222" class="my-upload-img">
                                    @if(!empty($model['intro_images']))
                                        @foreach($model['intro_images'] as $key => $item)
                                            <span class="self-add-img">
                                        <img src="{{$item}}">
                                        <input type="hidden" name="data[intro_images][]" value="{{$item}}">
                                        <span hidden="" class="img-delete">
                                            <i class="icon-shanchu iconfont"></i>
                                        </span>
                                    </span>
                                        @endforeach
                                    @endif
                                </li>
                                <li class="image-upload-add" data-num="10" data-box="image_box222" data-item='<span class="self-add-img"><img src=""><input type="hidden" name="data[intro_images][]" value=""><span hidden="" class="img-delete"><i class="icon-shanchu iconfont"></i></span></span>'>
                                    <a class="tra_photofile">上传图片</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="text-danger">*</span> 所在地区：</label>
                        <div class="col-xs-8">
                            <select name="data[province]" class="province" data-id="" >
                                <option selected>北京</option>
                            </select>
                            <select name="data[city]" class="city" data-id="" id="regionText">
                                <option selected>北京市</option>
                            </select>
                            <select name="data[district]" class="district" data-id="">
                                <option selected>东城区</option>
                            </select>
                            <p><small>所在地区将直接影响购买者在选择线下自提时的地区筛选，因此请如实认真选择全部地区级。</small></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="text-danger">*</span> 详细地址：</label>
                        <div class="col-xs-10">
                            <div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control w240 zoom" placeholder="请填写详细地址"  name="data[location]" id="poiText" value="">
                                    <input type="text" class="form-control  latitude" name="data[lat]"   hidden value="">
                                    <input type="text" class="form-control  longitude" name="data[lng]" hidden  value="">
                                    <a class="btn" id="seach">查找</a>
                                </div>
                                <div class="col-xs-2 G-p-0">
                                    <input type="button" value="标记新地址" title="搜不到部门地址时，可点击标记新部门，拖动地图标记地址" class="btn btn-info"  id = "changeSearchType">
                                </div>
                            </div>
                            <div>
                                <div id="infoDiv" class="col-xs-3" hidden>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-2 t_r"><span class="text-danger">*</span> 详细地址：</label>
                        <div class="col-xs-10">
                            <div id="container" class="mt10 col-xs-8" style="height:350px;width:550px;">
                                <!--地图-->
                            </div>
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
        });

    </script>
@stop
