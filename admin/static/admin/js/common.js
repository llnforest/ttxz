var set_time;
var sec = 3e3;
//弹出遮罩层
layui.use('layer', function(){
    layer = layui.layer;
});

//表单样式渲染（select）
layui.use('form', function(){
     form = layui.form;
});

layui.use('upload', function(){
    var upload = layui.upload;

    //执行实例，上传图片
    if($(".upload").length > 0){
        upload.render({
            elem: '.upload' //绑定元素
            ,field: 'file'
            ,size:0
            ,choose:function(){
                loading = layer.load(2, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            }
            ,done: function(res,index,upload){
                //上传完毕回调
                $(this)[0].item.find(".image").val(res.url);
                var path = $(this)[0].item.find(".mini-image").attr("data-path");
                $(this)[0].item.find(".mini-image").removeClass("hidden").attr("src",path+res.url);
                layer.close(loading);
            }
            ,error: function(index,upload){
                //请求异常回调
                console.log('wrong');
            }
        });
    }

    //执行实例，上传图片
    if($(".import").length > 0) {
        upload.render({
            elem: '.import' //绑定元素
            , field: 'excelfile'
            , exts:'xlsx'
            , size: 0
            ,before:function(){
                layer.load(2, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            }
            , done: function (res, index, upload) {
                //上传完毕回调
                if(res.code == 1){
                    $('#alert').html(alertSuccess(res.msg));
                    set_time = setTimeout(function() {
                        window.location.reload();
                    },sec);
                }else{
                    $('#alert').html(alertDanger(res.msg));
                }
                set_time = setTimeout(function() {
                    $('.close').click();
                    window.location.reload();
                },sec);
                layer.closeAll('loading');
            }
            , error: function (index, upload) {
                //请求异常回调
                layer.closeAll('loading');
            }
        });
    }
});

$(function() {

    renderPage();

    //渲染方法
    function renderPage(){
        bindEvent();//绑定事件
        orderSort();//排序
        renderSwitch();//渲染switch框
        renderDatePicker();//渲染日历
        renderPopover();//渲染向上弹框
        // renderForm();
    }

    //绑定事件的方法
    function bindEvent(){
        //span标签post提交(普通按钮、span标签)
        $('.span-post,.btn-post').click(function(){
            var _this = $(this);
            var msg = _this.attr('post-msg');
            var url = _this.attr('post-url');
            if(msg){
                layer.confirm(msg, {
                    btn: ['确定','取消'], //按钮
                    icon:7
                }, function(index){
                    layer.close(index);
                    spanPost(_this,url);
                }, function(index){
                    layer.close(index);
                });
            }else{
                spanPost(_this,url);
            }
        });

        //form表达提交(提交表单按钮、弹出层按钮)
        $(".form-post").click(function(){
            var _this = $(this);
            var dom = _this.parents('.form-horizontal');
            var postUrl = _this.attr('post-url');
            //按钮上的url优先
            var ajaxCallUrl = postUrl ? postUrl : dom.attr('action');
            var msg = _this.attr('post-msg');
            if(msg){
                layer.confirm(msg, {
                    btn: ['确定','取消'], //按钮
                    icon:7
                }, function(index){
                    layer.close(index);
                    formPost(_this,ajaxCallUrl,dom);
                }, function(index){
                    layer.close(index);
                });
            }else{
                formPost(_this,ajaxCallUrl,dom);
            }
        });

        //通过input框修改数据
        $(".change-data").focus(function (){
                $('#alert').html(alertDanger('输入一个新的数据来更改数据'));
                $(this).css("background-color", "#f2dede");
                change_data = $(this).val().trim();
            }
        ).blur(function(){
            var _this = $(this);
            _this.css("background-color", "#FFFFFF");
            var url  = _this.attr('post-url');
            var id   = _this.attr('post-id');
            var data = _this.val().trim();
            var name = _this.attr('data-name') || '';
            if(data == change_data) return false;
            $.ajax(
                {
                    url : url,
                    type : 'post',
                    dataType : 'json',
                    data : 'id=' + id + '&data=' + data + '&name=' + name,
                    success : function (json)
                    {
                        clearTimeout(set_time);
                        if(json.code == 1){
                            $('#alert').html(alertSuccess(json.msg));
                        }else if(json.code == 0){
                            $('#alert').html(alertDanger(json.msg));
                            _this.val(json.text);
                        }
                        if(json.url){
                            set_time = setTimeout(function() {
                                window.location.href=json.url;
                            },1000);
                        }
                        if(json.reload == 1){
                            set_time = setTimeout(function() {
                               location.reload();
                            },1000);
                        }
                        set_time = setTimeout(function() {
                            $('.close').click();
                        },sec);
                    },
                    beforeSend:function(){
                        loading = layer.load(2, {
                            shade: [0.1,'#fff'] //0.1透明度的白色背景
                        });
                    },
                    complete:function(){
                        layer.close(loading);
                    },
                    error:function(xhr){
                        $('#alert').html(alertDanger(xhr.responseText));
                    }
                });
        });

        //switch点击事件
        $("table").on("click",".layui-form-switch",function(){
            var _click = $(this);
            var _this = $(this).prev('input');
            var _input = _this.prev("input");
            var dom_value = _this.attr("data-value");
            var name = _this.attr("data-name");
            var value_arr = dom_value.split('|');
            if(_this.prop("checked")){
                _input.val(value_arr[0]);
                var data = value_arr[0];
            }else{
                _input.val(value_arr[1]);
                var data = value_arr[1];
            }
            var url = _this.attr("data-url");
            if(url){//有url异步操作时
                $.ajax(
                    {
                        url : url,
                        type : 'post',
                        dataType : 'json',
                        data : 'data=' + data + '&name=' + name ,
                        success : function (json)
                        {
                            clearTimeout(set_time);
                            if(json.code == 1){
                                $('#alert').html(alertSuccess(json.msg));
                            }else if(json.code == 0){
                                $('#alert').html(alertDanger(json.msg));
                                _this.removeAttr('data-url');
                                _click.click();
                            }
                            set_time = setTimeout(function() {
                                $('.close').click();
                            },sec);
                        },
                        beforeSend:function(){
                            loading = layer.load(2, {
                                shade: [0.1,'#fff'] //0.1透明度的白色背景
                            });
                        },
                        complete:function(){
                            layer.close(loading);
                        },
                        error:function(xhr){
                            $('#alert').html(alertDanger(xhr.responseText));
                        }
                    });
            }
        })

        //排序
        $('.order-sort').click(function(){
            console.log(1);
            var dom = $(this);
            url = window.location.href;
            url = url.split('?order')[0];
            var first = '?';
            var order = dom.attr('order');
            if(url.indexOf('?') > -1) first = '&';
            if(!dom.hasClass('order-sort-up') ){
                url = url+first+'order='+order+'&by=asc';
            }else{
                url = url+first+'order='+order+'&by=desc';
            }
            window.location.href = url;
        });

        //关闭弹框
        $(".layui-off").click(function(){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index);
        });

        //--------------点击输入框渲染功能-------------
        //点击输入框显示列表
        $('.click-show').bind("input propertychange",function(){
            var _this = $(this);
            var name = _this.val().trim();
            var data_id = _this.next(".click-id").val();
            var data_url = _this.attr("data-url");
            var data_msg = _this.attr("data-msg");
            ajaxPost(data_url
                ,{name:name}
                ,function(data){
                    var html = ''
                    if(data.code == 1){
                        $.each(data.data,function(index,item){
                            var active = item.id == data_id ? 'active':'';
                            html += '<li class="list-group-item click-show-list '+active+'" data-id="'+item.id+'">'+item.name+'</li>'
                        })
                    }else if(data.code == 0){
                    }else{
                        html = '<li class="list-group-item click-show-list disabled" data-id="">没有找到您匹配的'+data_msg+'</li>';
                    }
                    _this.siblings('.click-show-wrap').html(html);
                }
            )
        })

        //点击列表中选项
        $(".click-show-wrap").on('click','.click-show-list:not(".disabled")',function(){
            var _this = $(this);
            var id = _this.attr("data-id");
            var text = _this.text();
            _this.parent(".click-show-wrap").siblings('.click-id').val(id);
            _this.parent(".click-show-wrap").siblings('.click-show').val(text);
            _this.parent(".click-show-wrap").html('');
        })
        //点击列表中不可点击选项
        $(".click-show-wrap").on('click','.click-show-list.disabled',function(){
            var _this = $(this);
            _this.parent(".click-show-wrap").siblings('.click-id').val('');
            _this.parent(".click-show-wrap").siblings('.click-show').val('');
            _this.parent(".click-show-wrap").html('');
        })

    }
})
//渲染排序样式
function orderSort(){
    if($('.order-sort').length > 0){
        $('.order-sort').each(function(){
            var dom = $(this);
            var order = dom.attr('order');
            url = window.location.href;
            if(url.indexOf('order='+order+'&by=asc')>-1){
                dom.addClass('order-sort-up');
            }else if(url.indexOf('order='+order+'&by=desc')>-1){
                dom.addClass('order-sort-down');
            }
        })
    }
}
function toggles(obj,act,id){
    var url = $('.toggleUrl').val();
    val = ($(obj).attr('src').match(/yes.png/i)) ? 0 : 1;
    $.ajax(
        {
            url         : url,
            type        : 'post',
            dataType    : 'json',
            data        : 'val='+val+'&act='+act+'&id='+id,
            success : function (json)
            {
                if(json.code == 1){
                    var imgsrc = (json.data == 1 ) ? '/statics/admin/images/yes.png' : '/statics/admin/images/no.png';

                    $(obj).attr('src',imgsrc);

                }else if(json.code == 0){
                    $('#alert').html(alertDanger(json.msg));
                }
            },
            error:function(xhr){
                $('#alert').html(alertDanger(xhr.responseText));

            }
        });
}

//渲染form表单样式
function renderForm(){
    form.render('select');
}

//成功提示框
function alertSuccess(data){
    return '<div class="alert alert-success" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+data+'</div>';
}

//错误提示框
function alertDanger(data){
    return '<div class="alert alert-danger" role="alert" style="overflow-y: auto;max-height: 600px;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>'+data+'</div>';
}

//获取url地址
function getUrl(url,data){
    var temp = '';
    for(var value in data){
        temp += '/'+value+'/'+data[value];
    }
    temp += '.html'
    return '/admin/'+url+temp;
}

//获取url上的参数值
function getQueryString(name) { 
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
    var r = window.location.search.substr(1).match(reg); 
    if (r != null) return r[2];
    return '';
}

//单个按钮异步方法
function spanPost(_this,url,data){
    data = data || [];
    console.log(data);
    $.ajax(
        {
            url : url,
            data: data,
            type : 'post',
            dataType : 'json',
            success : function (json)
            {
                clearTimeout(set_time);
                if(json.code == 1){
                    $('#alert').html(alertSuccess(json.msg));
                    set_time = setTimeout(function(){
                        if($(window.parent.document).find(".layui-layer").length > 0){
                            window.parent.location.reload();
                            $(window.parent.document).find(".layui-layer").remove();
                        }
                        if(json.url){
                            set_time = setTimeout(function() {
                                window.location.href=json.url;
                            },1000);
                        }else{
                            location.reload();
                        }
                    },3e3)
                }else if(json.code == 0){
                    $('#alert').html(alertDanger(json.msg));
                }
                set_time = setTimeout(function() {
                    $('.close').click();
                },sec);
            },
            beforeSend:function(){
                if(_this.hasClass("btn")) _this.button("loading");
                loading = layer.load(2, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });
            },
            complete:function(){
                if(_this.hasClass("btn")) _this.button("reset");
                layer.close(loading);
            },
            error:function(xhr){          //上传失败
                $('#alert').html(alertDanger(xhr.responseText));
            }
        });
}

//表单异步提交方法
function formPost(_this,url,dom){
    $.ajax({
        url : url,
        type : 'post',
        dataType : 'json',
        data : dom.serialize(),
        success: function(json) {
            clearTimeout(set_time);
            if(json.code == 1){
                $('#alert').html(alertSuccess(json.msg));
                if (json.alert){
                    layer.confirm(json.alert, {
                        btn: ['确定','取消'], //按钮
                        icon:1
                    }, function(index){
                        layer.close(index);
                        if($(window.parent.document).find(".layui-layer").length > 0){
                            window.parent.location.reload();
                            $(window.parent.document).find(".layui-layer").remove();
                        }
                        if(json.parents_url){
                            window.parent.location.href=json.parents_url;
                        }
                        if(json.url) window.location.href=json.url;
                    }, function(index){
                        layer.close(index);
                    });
                }else{
                    setTimeout(function(){
                        if($(window.parent.document).find(".layui-layer").length > 0){
                            window.parent.location.reload();
                            $(window.parent.document).find(".layui-layer").remove();
                        }
                        if(json.parents_url){
                            window.parent.location.href=json.parents_url;
                        }
                        if(json.url) window.location.href=json.url;
                    },sec)
                }

            }else if(json.code == 0){
                $('#alert').html(alertDanger(json.msg));
            }
            set_time = setTimeout(function() {
                $('.close').click();
            },sec);
        },
        beforeSend:function(){
            _this.button("loading");
            loading = layer.load(2, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
        },
        complete:function(){
            _this.button("reset");
            layer.close(loading);
        },
        error:function(xhr){//失败
            $('#alert').html(alertDanger(xhr.responseText));
        }
    });
}

//渲染switch框
function renderSwitch(){
    var dom = $("input[lay-skin='switch']");
    if(dom.length > 0){
        dom.each(function(){
            var dom_name = $(this).attr("data-name");
            var dom_value = $(this).attr("data-value");
            var value_arr = dom_value.split('|');
            var value = $(this).prop("checked")?value_arr[0]:value_arr[1];
            $(this).before('<input name="'+dom_name+'" type="hidden" value="'+value+'">');
        })
    }
}

//渲染日历方法
function renderDatePicker(){
    if($(".date-time").length > 0){
        //时间日期渲染
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            $(".date-time").each(function(){
                var dom = '.'+$(this).attr("dom-class");
                var format = $(dom).attr("dom-format") || 'yyyy-MM-dd';
                var type = $(dom).attr("dom-type") || 'date';
                laydate.render({
                    elem:dom,
                    format:format,
                    type:type
                })
            })
        });
    }
}

//渲染向上弹框
function renderPopover(){
    if($("[data-toggle='popover']").length > 0){
        $("[data-toggle='popover']").popover();
    }
}

//发送异步post请求
function ajaxPost(url,data,successFunc){
    $.ajax(
        {
            url : url,
            data: data,
            type : 'post',
            dataType : 'json',
            success : function (json){
                successFunc(json);
            },
            error:function(xhr){          //上传失败
                $('#alert').html(alertDanger(xhr.responseText));
            }
        });
}

