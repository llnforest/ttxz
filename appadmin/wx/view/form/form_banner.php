
<div class="col-sm-12">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>banner图片</th>
                <td>
                    <button name="image" type="button" class="layui-btn upload" lay-data="{'url': '{:url('index/upload/image',['type'=>'banner'])}'}">
                        <i class="layui-icon">&#xe67c;</i>上传banner图
                        <input class="image" type="hidden" name="url" value="{$info.url??''}">
                        <img class="mini-image {$info.url?'':'hidden'}" data-path="__ImagePath__" src="{$info.url?'__ImagePath__'.$info.url:''}">
                    </button>
                </td>
            </tr>
            <tr>
                <th>banner状态</th>
                <td class="layui-form">
                    <input type="checkbox" data-name="status" lay-skin="switch" lay-text="上架|下架" {if !isset($info.status) || $info.status == 1}checked{/if} data-value="1|0">
                </td>
            </tr>
            <tr>
                <th>banner标题</th>
                <td>
                    <input class="form-control text" type="text" name="name" value="{$info.name??''}" placeholder="banner标题">
                </td>
            </tr>
            <tr>
                <th>banner排序</th>
                <td>
                    <input class="form-control text" type="text" name="sort" value="{$info.sort??''}" placeholder="banner排序">
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

