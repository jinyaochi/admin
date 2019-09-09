define(function(require, exports, module) {
    require('datepicker.css');
    var jqdatePicker = require('datepicker.date');
    var layuiCss = require('layui.css');
    var layuiJS = require('layui');
    var form = null;
    var guide = '';
    layui.use(['form', 'layedit', 'laydate', 'layer'], function() {
        form = layui.form;
        var layer = layui.layer,
            layedit = layui.layedit,
            laydate = layui.laydate;
        form.on('checkbox', function(data) {
            if (data.elem.checked == true) {
                $(data.othis).parent().siblings().find('input').prop('disabled', false)
                $(data.othis).parent().siblings().find('select').prop('disabled', false)
            } else {
                $(data.othis).parent().siblings().find('input').prop('disabled', true)
                $(data.othis).parent().siblings().find('select').prop('disabled', true)

            }
        })
        form.on('select(stores)', function(data) {
            $('#guide .layui-input').val('')
            $("#guide").find("option").eq(0).attr("selected", true);
            $('#guide option').each(function(index) {
                if (index != 0) {
                    if ($('#guide option').eq(index).attr('data-store') == data.value) {
                        $('#guide option').eq(index).show();
                        $('#guide .layui-anim').find('dd').eq(index).show();
                    } else {
                        $('#guide option').hide();
                        $('#guide .layui-anim').find('dd').eq(index).hide();
                    }
                }
            })
        })
        form.render();

    });

    layui.use('colorpicker', function() {

        var colorpicker = layui.colorpicker;
        //表单赋值
        colorpicker.render({
            elem: '#titleColor',
            color: '#333',
            done: function(color) {
                $('#test-form-input').val(color);
            }
        });
    });
    layui.use('element', function() {
        var element = layui.element;

    });
    layui.use(['laypage', 'layer'], function() {
        var laypage = layui.laypage,
            layer = layui.layer;

        //不显示首页尾页
        laypage.render({
            elem: 'paging-goods',
            count: 10,
            limit: 5,
            prev: '<i class="fa fa-caret-left"></i>',
            next: '<i class="fa fa-caret-right"></i>',
            first: false,
            last: false
        });

    });

    var t = function(e, i) {
        $(function() {
            // $('#guide option').each(function(index){
            //     if(index != 0){
            //         if($('#guide option').eq(index).attr('data-store') == $("#store option:selected").val()){
            //             $('#guide option').eq(index).show();
            //             $('#guide .layui-anim').find('dd').eq(index).show();
            //
            //         }else{
            //             $('#guide option').hide();
            //             $('#guide .layui-anim').find('dd').eq(index).hide();
            //         }
            //     }
            // })
            var newValue;
            var index;
            selectIndex()
            $('.entrance-table tr').find('.btn-select-link').on('click', function() {
                index = $(this).parents('tr').index()
            })
            $('.btn-determine').on('click', function() {
                $('.entrance-table tr').eq(index).find('.valid-input').val(newValue)
            })

            $('.links-ul li').on('click', function() {
                $(this).addClass('active').siblings().removeClass('active');
                $('.tagContentBox .tagContent').eq($(this).index()).show().siblings().hide();
            });
            // 日期
            var DATAPICKERAPI = {
                // 默认input显示当前月,自己获取后填充
                activeMonthRange: function() {
                    return {
                        begin: moment().set({
                            'date': 1,
                            'hour': 0,
                            'minute': 0,
                            'second': 0
                        }).format('YYYY-MM-DD HH:mm:ss'),
                        end: moment().set({
                            'hour': 23,
                            'minute': 59,
                            'second': 59
                        }).format('YYYY-MM-DD HH:mm:ss')
                    }
                },
                shortcutMonth: function() {
                    // 当月
                    var nowDay = moment().get('date');
                    var prevMonthFirstDay = moment().subtract(1, 'months').set({
                        'date': 1
                    });
                    var prevMonthDay = moment().diff(prevMonthFirstDay, 'days');
                    return {
                        now: '-' + nowDay + ',0',
                        prev: '-' + prevMonthDay + ',-' + nowDay
                    }
                },
                // 注意为函数：快捷选项option:只能同一个月份内的
                rangeMonthShortcutOption1: function() {
                    var result = DATAPICKERAPI.shortcutMonth();
                    return [{
                        name: '昨天',
                        day: '-1,-1',
                        time: '00:00:00,23:59:59'
                    }, {
                        name: '这一月',
                        day: result.now,
                        time: '00:00:00,'
                    }, {
                        name: '上一月',
                        day: result.prev,
                        time: '00:00:00,23:59:59'
                    }];
                },
                // 快捷选项option
                rangeShortcutOption1: [{
                    name: '最近一周',
                    day: '-7,0'
                }, {
                    name: '最近一个月',
                    day: '-30,0'
                }, {
                    name: '最近三个月',
                    day: '-90, 0'
                }],
                // 快捷选项option
                rangeShortcutOption2: [{
                    name: '最近一周',
                    day: '0,7'
                }, {
                    name: '最近一个月',
                    day: '0,30'
                }],
                singleShortcutOptions1: [{
                    name: '今天',
                    day: '0'
                }, {
                    name: '昨天',
                    day: '-1',
                    time: '00:00:00'
                }, {
                    name: '一周前',
                    day: '-7'
                }]
            };

            //年月日单个
            $('.J-datepicker-day').datePicker({
                hasShortcut: true,
                min: false,
                max: false,
                format: 'YYYY-MM-DD HH:mm:ss',
                shortcutOptions: [{
                    name: '今天',
                    day: '0',
                }, {
                    name: '昨天',
                    day: '-1'
                }, {
                    name: '一周前',
                    day: '-7'
                }]
            });
            // 今天
            $('.J-datepicker-days').datePicker({
                hasShortcut: true,
                min: '2000-01-01',
                max: getNewDateArry(true, 0)[0] + '-' + getNewDateArry(true, 0)[1] + '-' + getNewDateArry(true, 0)[2] + ' ' + getNewDateArry(true, 0)[3] + ':' + getNewDateArry(true, 0)[4],
                format: 'YYYY-MM-DD',
                shortcutOptions: [{
                    name: '今天',
                    day: '0'
                }, {
                    name: '昨天',
                    day: '-1'
                }, {
                    name: '一周前',
                    day: '-7'
                }],
                show: function() {
                    $('.c-datepicker-date-table .available.test').addClass('disabled')
                }
            });
            // 今天
            $('.J-datepicker-before-days').datePicker({
                hasShortcut: true,
                min: getNewDateArry(true, 0)[0] + '-' + getNewDateArry(true, 0)[1] + '-' + getNewDateArry(true, 0)[2] + ' ' + getNewDateArry(true, 0)[3] + ':' + getNewDateArry(true, 0)[4],
                max: false,
                format: 'YYYY-MM-DD',
                shortcutOptions: [{
                    name: '今天',
                    day: '0'
                }, {
                    name: '昨天',
                    day: '-1'
                }, {
                    name: '一周前',
                    day: '-7'
                }],
                show: function() {
                    console.log($('.c-datepicker-date-table .available'));
                    $('.c-datepicker-date-table .available.test').addClass('disabled')
                }
            });
            //十分秒年月日范围，包含最大最小值
            $('.J-datepicker-range-day').datePicker({
                hasShortcut: true,
                min: false,
                max: false,
                isRange: true,
                shortcutOptions: [{
                    name: '昨天',
                    day: '-1,-1',
                    time: '00:00:00,23:59:59'
                }, {
                    name: '最近一周',
                    day: '-7,0',
                    time: '00:00:00,'
                }, {
                    name: '最近一个月',
                    day: '-30,0',
                    time: '00:00:00,'
                }, {
                    name: '最近三个月',
                    day: '-90, 0',
                    time: '00:00:00,'
                }]
            });
            //没有时分秒 没有最大最小值限制  多个
            $('.J-datepicker-single-day').datePicker({
                hasShortcut: true,
                min: false,
                max: false,
                isRange: true,
                format: 'YYYY-MM-DD',
                shortcutOptions: [{
                    name: '昨天',
                    day: '-1,-1',
                    time: '00:00:00,23:59:59'
                }, {
                    name: '最近一周',
                    day: '-7,0',
                    time: '00:00:00,'
                }, {
                    name: '最近一个月',
                    day: '-30,0',
                    time: '00:00:00,'
                }, {
                    name: '最近三个月',
                    day: '-90, 0',
                    time: '00:00:00,'
                }]
            });
            $('.tag-delete').on('click', function() {
                var _this = $(this);

                message.confirm('确定要删除吗', function() {
                    $.ajax({
                        url: _this.attr('data-url'),
                        type: 'POST',
                        dataType: 'json',
                        async: true,
                        data: {
                            id: _this.attr('data-id'),
                            tag: _this.attr('data-tag')
                        },
                        success: function(res) {
                            if (res.code == 1) {
                                $(_this).remove();
                            } else {
                                message.error(res.msg)
                            }
                        }
                    });
                    message.closeAll()
                });

            });
            // 年月日范围(没有时秒&&限制在一个月范围内)
            $('.J-datepicker-range-day-nohours-limit30').datePicker({
                hasShortcut: true,
                format: 'YYYY-MM-DD',
                isRange: true,
                shortcutOptions: DATAPICKERAPI.rangeShortcutOption2,
                min: getNewDateArry(false, 0)[0] + '-' + getNewDateArry(false, 0)[1] + '-' + getNewDateArry(false, 0)[2] + ' ' + getNewDateArry(false, 0)[3] + ':' + getNewDateArry(false, 0)[4],
                max: getNewDateArry(true, 30)[0] + '-' + getNewDateArry(true, 30)[1] + '-' + getNewDateArry(true, 30)[2] + ' ' + getNewDateArry(true, 30)[3] + ':' + getNewDateArry(true, 30)[4],
            });
            $('.J-datepicker-range-day-nohours-limit5').datePicker({
                hasShortcut: true,
                format: 'YYYY-MM-DD HH:mm:ss',
                isRange: true,
                shortcutOptions: DATAPICKERAPI.rangeShortcutOption2,
                max: addYear(0)[0] + '-' + addYear(0)[1] + '-' + addYear(0)[2] + ' ' + addYear(0)[3] + ':' + addYear(0)[4],
                min: addYear(5)[0] + '-' + addYear(5)[1] + '-' + addYear(5)[2] + ' ' + addYear(5)[3] + ':' + addYear(5)[4],
            });

            $('.J-datepicker-range-month-nohours-limit3').datePicker({
                hasShortcut: true,
                format: 'YYYY-MM-DD HH:mm:ss',
                isRange: true,


                shortcutOptions: DATAPICKERAPI.rangeShortcutOption2,
                max: addMonth(-1)[0] + '-' + addMonth(-1)[1] + '-' + addMonth(-1)[2] + ' ' + addMonth(-1)[3] + ':' + addMonth(-1)[4],
                min: addMonth(2)[0] + '-' + addMonth(2)[1] + '-' + addMonth(2)[2] + ' ' + addMonth(2)[3] + ':' + addMonth(2)[4],
            });



            checkedFn('alluser', 'user', '.stylecheckbox')
            $('.radio-style input').on('change', function(event) {
                // $(this).siblings('i').addClass('layui-icon-radio').parent('.box').siblings('li').find('i').removeClass('layui-icon-radio');
                $(this).siblings('i').addClass('layui-icon-radio').parent('.box').siblings().find('i').removeClass('layui-icon-radio');
                event.stopPropagation();
            })

        });


        function selectIndex() {
            $('.link-list li .last').on('click', function() {
                $(this).text('已选').parents('li').siblings().find('.last').text('选择链接');
                $(this).addClass('selected-link').parents('li').siblings().find('.last').removeClass('selected-link');
                var link = $(this).parents('ul').find('li').eq(0).find('.list-tit').eq(1).text()
                newValue = link + ' > ' + $(this).siblings('.ng-binding').text()
            })
        }

        function checkedFn(all, single, cname) {
            $("input[name=" + all + "]").on('change', function() {
                if ($(this).prop('checked') == true) {
                    $(cname).find("input[type='checkbox']").prop('checked', true);
                } else {
                    $(cname).find("input[type='checkbox']").prop('checked', false);
                }
            })

            $(cname).on('click', function() {
                if ($(cname).find('input[type="checkbox"]:checked').length == $(cname + ' input[type="checkbox"]').length) {
                    $("input[name=" + all + "]").prop("checked", true)
                } else {
                    $("input[name=" + all + "]").prop("checked", false)
                }
            })
        }

        function getNewDateArry(bol, num) {
            // 当前时间的处理
            var newDate = new Date();
            var year = withData(newDate.getFullYear());
            var mont = withData(newDate.getMonth() + 1);
            var date = withData(newDate.getDate());
            var hour = withData(newDate.getHours());
            var minu = withData(newDate.getMinutes());
            var seco = withData(newDate.getSeconds());
            if (bol) {
                var date2 = new Date(newDate)
                date2.setDate(newDate.getDate() + num);
                var year = withData(date2.getFullYear());
                var mont = withData(date2.getMonth() + 1);
                var date = withData(date2.getDate());
                var hour = withData(date2.getHours());
                var minu = withData(date2.getMinutes());
                var seco = withData(date2.getSeconds());
            }
            return [year, mont, date, hour, minu, seco];
        }

        function addYear(num) {
            var date2 = new Date();
            var year = withData(date2.getFullYear() - num);
            var mont = withData(date2.getMonth() + 1);
            var date = withData(date2.getDate());
            var hour = withData(date2.getHours());
            var minu = withData(date2.getMinutes());
            var seco = withData(date2.getSeconds());
            // console.log([year, mont, date, hour, minu, seco])
            return [year, mont, date, hour, minu, seco];
        }

        function addMonth(num) {
            var date2 = new Date();
            var year = withData(date2.getFullYear());
            var mont = withData(date2.getMonth() - num);
            var date = withData(date2.getDate());
            var hour = withData(date2.getHours());
            var minu = withData(date2.getMinutes());
            var seco = withData(date2.getSeconds());
            return [year, mont, date, hour, minu, seco];
        }

        function withData(param) {
            return param < 10 ? '0' + param : '' + param;
        }
        $('.reset_form').on('click', function() {
            $(this).parents('.form-group').siblings('.form-group').find('.js_keyword').val('');
            $(this).parents('.form-group').siblings('.form-group').find('.select-change-style').val('');
            $(this).parents('.form-group').siblings('.form-group').find('.c-datepicker-date-editor input').val('');
        })
    };
    exports.bootstrap = function(e, i) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });
        $(t(e, i))
    }

})
