$(function(){
    var pick = '#videoupload',
        uploadUrl = $(pick).attr('data-url'),
        beforeUrl = $(pick).attr('data-base'),
        successUrl = $(pick).attr('data-success'),
        GUID = WebUploader.Base.guid()
        id = $(pick).attr("data-id");

    /** 实现webupload hook，触发上传前，中，后的调用关键 **/
    WebUploader.Uploader.register({
        "before-send-file": "beforeSendFile",  // 整个文件上传前
        "before-send": "beforeSend",           // 每个分片上传前
        "after-send-file": "afterSendFile",     // 分片上传完毕
    }, {
        beforeSendFile: function (file) {
            var task = new $.Deferred();
            $.ajax({
                type: "POST",
                url: beforeUrl,   // 后台url地址
                data: {
                    id: id,
                    guid:GUID,
                    size:file.size,
                    name:file.name
                },
                cache: false,
                async: false,  // 同步
                timeout: 1000, //todo 超时的话，只能认为该文件不曾上传过
                dataType: "json",
                beforeSend: function(){
                    $("#progress").css({top:0,bottom:0});
                    $("#progress").show();
                    $("#videoupload").addClass("disabled").text("正在上传中");
                }
            }).then(function (data, textStatus, jqXHR) {
                if (data.code == 1) { //若存在，这返回失败给WebUploader，表明该文件不需要上传
                    alreadyChunks = data.chunkList;
                    fileId = data.fileId;
                    filePath = data.filePath;
                    task.resolve();
                    // // 业务逻辑...
                } else {
                    task.reject();
                }
            }, function (jqXHR, textStatus, errorThrown) { //任何形式的验证失败，都触发重新上传
                task.reject();
            });
            return $.when(task);
        }
        , beforeSend: function (block) {
            //分片验证是否已传过，用于断点续传
            var task = new $.Deferred();
            if($.inArray(block.chunk,alreadyChunks) >= 0){
                task.reject();
            }else{
                task.resolve();
            }
            return $.when(task);
        }
        , afterSendFile: function (file) {
                //合并请求
                var task = new $.Deferred();
                $.ajax({
                    type: "POST",
                    url: successUrl,
                    data: {
                        fileId: fileId,
                        filePath: filePath,
                    },
                    cache: false,
                    async: false,  // 同步
                    dataType: "json"
                }).then(function (data, textStatus, jqXHR) {
                    // 业务逻辑...
                    if(data.code == 1){
                        task.resolve();
                    }else{
                        task.reject();
                    }
                }, function (jqXHR, textStatus, errorThrown) {
                    task.reject();
                });
                return $.when(task);
            }
    });

    var uploader = new WebUploader.Uploader({
        auto: true,
        swf:'/webuploader/Uploader.swf',
        // 文件接收服务端。
        server: uploadUrl,
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: pick,
        accept:{
            extensions:'mp4',
        },
        chunked: true,//开启分片上传
        chunkSize : 2*1024*1024,
        threads: 1,//上传并发数
        fileNumLimit:1,
        fileSizeLimit:2*1024*1024*1024,
        //由于Http的无状态特征，在往服务器发送数据过程传递一个进入当前页面是生成的GUID作为标示
        formData: {guid:GUID,oid:id}
    });
    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', function (file, percentage) {
        var rate = percentage * 100 .toFixed(1);
        $('#progress .bar').css('width', rate + '%');
    });

    // 文件上传成功处理。
    uploader.on('uploadSuccess', function (file, response) {
        $('#alert').html(alertSuccess("上传成功"));
    });

    // 文件上传失败处理。
    uploader.on('uploadError', function (file) {
        $('#alert').html(alertDanger('上传失败'));
    });

    // 长传完毕，不管成功失败都会调用该事件，主要用于关闭进度条
    uploader.on('uploadComplete', function (file) {
        $("#progress").hide();
        $('#progress .bar').css('width', '0%');
        setTimeout(function() {
            $('.close').click();
            window.location.reload();
        },2000);
    });

    uploader.on('error',function(type){
        if(type == 'Q_TYPE_DENIED'){
            $('#alert').html(alertDanger('请上传mp4格式的视频'));
        }else if(type == 'Q_EXCEED_SIZE_LIMIT'){
            $('#alert').html(alertDanger('一次只能上传一个视频'));
        }else if(type == 'Q_EXCEED_SIZE_LIMIT'){
            $('#alert').html(alertDanger('视频大小不能超过2G'));
        }
        setTimeout(function() {
            $('.close').click();
        },3000);
    })

});