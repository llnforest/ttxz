if($(".address-area").length > 0){
    // var defaults = {
    //     s1: 'prov',
    //     s2: 'city',
    //     s3: 'area',
    //     v1: null,
    //     v2: null,
    //     v3: null
    // };
    // var $form;
    var form;
    // var $;
    layui.define(['jquery', 'form'], function () {
        // $ = layui.jquery;
        form = layui.form;
        $(".address-area").each(function(){
            var _this = $(this);
            $form = _this;
            var address_defaults = {
                s1: _this.attr("data-prov"),
                s2: _this.attr("data-city"),
                s3: _this.attr("data-area"),
                v1: _this.attr("data-provid"),
                v2: _this.attr("data-cityid"),
                v3: _this.attr("data-areaid")
            }
            treeSelect(address_defaults);
        })
    });
    function treeSelect(config) {
        config.v1 = config.v1 ? config.v1 : 340000;
        config.v2 = config.v2 ? config.v2 : 340100;
        config.v3 = config.v3 ? config.v3 : 340101;
        $.each(threeSelectData, function (k, v) {
            appendOptionTo($form.find('select[name=' + config.s1 + ']'), k, v.val, config.v1);
        });
        form.render();
        cityEvent('',config);
        form.on('select(' + config.s1 + ')', function (data) {
            cityEvent(data,config);
        });

        function cityEvent(data,config) {
            $('select[name=' + config.s2 + ']').html("");
            config.v1 = data.value ? data.value.split('_')[0] : config.v1;
            $.each(threeSelectData, function (k, v) {
                if (v.val == config.v1) {
                    if (v.items) {
                        $.each(v.items, function (kt, vt) {
                            appendOptionTo($('select[name=' + config.s2 + ']'), kt, vt.val, config.v2);
                        });
                    }
                }
            });
            form.render();
            config.v2 = $('select[name=' + config.s2 + ']').val();
            config.s2 = data.s2 ? data.s2 : config.s2;
            form.on('select(' + config.s2 + ')', function (data) {
                areaEvent(data,config);
            });
            areaEvent(config,config);
        }
        function areaEvent(data,config) {
            $('select[name=' + config.s3 + ']').html("");
            config.v2 = data.value ? data.value.split('_')[0] : config.v2.split('_')[0];
            $.each(threeSelectData, function (k, v) {
                if (v.val == config.v1) {
                    if (v.items) {
                        $.each(v.items, function (kt, vt) {
                            if (vt.val == config.v2) {
                                $.each(vt.items, function (ka, va) {
                                    appendOptionTo($('select[name=' + config.s3 + ']'), ka, va, config.v3);
                                });
                            }
                        });
                    }
                }
            });
            form.render();
            form.on('select(' + config.s3 + ')', function (data) { });
        }
        function appendOptionTo($o, k, v, d) {
            var value = v+'_'+k;
            var $opt = $("<option>").text(k).val(value);
            if (v == d) { $opt.attr("selected", "selected") }
            $opt.appendTo($o);
        }
    }
}
