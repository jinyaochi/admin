define(function (require, exports, module) {

    let OSS = require('oss');

    let ossConfig = {
        region: '<Your region>',
        accessKeyId: '<Your AccessKeyId>',
        accessKeySecret: '<Your AccessKeySecret>',
        bucket: 'Your bucket name'
    }

    let client = new OSS(ossConfig);

    let tempCheckpoint;

    // 定义上传方法
    async function multipartUpload () {
        try {
            let result = await client.multipartUpload('object-key', 'local-file', {
                progress: async function (p, checkpoint) {
                    // 断点记录点。 浏览器重启后无法直接继续上传，需用户手动触发进行设置。
                    tempCheckpoint = checkpoint;
                },
                meta: { year: 2017, people: 'test' },
                mime: 'image/jpeg'
            })
        } catch(e){
            console.log(e);
        }
    }

    multipartUpload();

    function send_request()
    {
        var getData = null;
        $.ajax({
            url: '/system/alert/oss/auth',
            data: {},
            async: false,
            dataType:'json',
            success: function(json){
                if(!json.accessid){
                    return message.error(json.message);
                }
                getData = json;
            }
        });

        return getData;

    };
});