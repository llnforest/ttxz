<script type="text/javascript" charset="utf-8" src="__PublicAdmin__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PublicAdmin__/ueditor/ueditor.all.min.js"> </script>
<div class="col-sm-12">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>活动状态</th>
                <td class="layui-form">
                    <input type="checkbox" data-name="status" lay-skin="switch" lay-text="正常|停用" {if !isset($info.status) || $info.status == 1}checked{/if} data-value="1|0">
                </td>
            </tr>
            <tr>
                <th>小程序appid</th>
                <td>
                    <input class="form-control text" type="text" name="appid" value="{$info.appid??''}" placeholder="小程序appid">
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th>小程序secret</th>
                <td>
                    <input class="form-control text" type="text" name="secret" value="{$info.secret??''}" placeholder="小程序secret">
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th>客服微信号</th>
                <td>
                    <input class="form-control text" type="text" name="service_weixin" value="{$info.service_weixin??''}" placeholder="客服微信号">
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th style="width:200px;">客服二维码</th>
                <td>
                    <button name="image" type="button" class="layui-btn upload" lay-data="{'url': '{:url('index/upload/image',['type'=>'config'])}'}">
                        <i class="layui-icon">&#xe67c;</i>上传客服二维码
                        <input class="image" type="hidden" name="service_url" value="{$info.service_url??''}">
                        <img class="mini-image {$info.service_url?'':'hidden'}" data-path="__ImagePath__" src="{$info.service_url?'__ImagePath__'.$info.service_url:''}">
                    </button>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <button type="button" class="btn btn-success form-post " >保存</button>
                    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    var ue = UE.getEditor('content');
</script>

