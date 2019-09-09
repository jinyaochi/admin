define(function(require, exports, module) {
    require('layui.css');
    require('layui');
    layui.use(['form', 'layedit','layer'], function () {
        var form = layui.form;
        form.render();
    });
    var t = function(e,i) {

        $('#seachuser2').click(function () {
            $.ajax({
                url: '/school/manage/index/user/search',
                type: 'POST',
                dataType: 'JSON',
                async: true,
                data: { mobile: $('#searchinput').val() ,type:'worker'},
                success: function(data) {
                    if(!data.status){
                        $('#searchinput').val('')
                        $('#adminid').val('')
                        return message.error(data.info)
                    }
                    $('#adminid').val(data.data.id)
                    message.success('当前账号可用');
                },
                error: function() {

                }
            });
        });

        $('#seachuser').click(function () {
            $.ajax({
                url: '/school/manage/index/user/search',
                type: 'POST',
                dataType: 'JSON',
                async: true,
                data: { mobile: $('#searchinput').val() },
                success: function(data) {
                    if(!data.status){
                        $('#searchinput').val('')
                        $('#adminid').val('')
                        return message.error(data.info)
                    }
                    $('#adminid').val(data.data.id)
                    message.success('当前账号可用');
                },
                error: function() {

                }
            });
        });

        $('#province').change(function() {
            var href = $(this).data('href');
            var next = $(this).data('next');

            if (next && href && $(this).val()) {
                $.ajax({
                    url: href,
                    type: 'POST',
                    dataType: 'JSON',
                    async: true,
                    data: { province: $('#province').val() },
                    success: function(data) {
                        $('#' + next).html('<option value="0">--请选择--</option>');
                        $('#region').html('<option value="0">--请选择--</option>');
                        for (var i in data) {
                            $('#' + next).append('<option value="' + data[i].code + '">' + data[i].name + '</option>');
                        }
                    },
                    error: function() {

                    }
                });
            }
        });

        $('#city').change(function() {
            var href = $(this).data('href');
            var next = $(this).data('next');

            if (next && href && $(this).val()) {
                $.ajax({
                    url: href,
                    type: 'POST',
                    dataType: 'JSON',
                    async: true,
                    data: { province: $('#province').val(), city: $('#city').val() },
                    success: function(data) {
                        $('#' + next).html('<option value="0">--请选择--</option>');
                        for (var i in data) {
                            $('#' + next).append('<option value="' + data[i].code + '">' + data[i].name + '</option>');
                        }
                    },
                    error: function() {

                    }
                });
            }
        });

        $('#schools').on('change',function () {
            $.ajax({
                url: '/school/manage/workerlist',
                type: 'POST',
                dataType: 'JSON',
                async: true,
                data: { schoolid: $('#schools').val()},
                success: function(data) {
                    $('#workers').html('<option value="0">选择业务员</option>');
                    for (var i in data.data) {
                        $('#workers').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
                    }
                },
                error: function() {

                }
            });
        });

    };

    exports.bootstrap = function(e, i) {
        $(t(e, i))
    }

});
