
<?php $status = isset($info['status'])?$info['status']:'';?>
<div class="col-sm-12">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>角色名称</th>
            <td>
                <input class="form-control text" type="text" name="name" value="<?php echo isset($info['name'])?$info['name']:'';?>">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>角色状态</th>
            <td class="layui-form">
                <input type="checkbox" data-name="status" lay-skin="switch" lay-text="开启|禁用" <?php echo $status!==0?'checked':''?> data-value="1|0">
            </td>
        </tr>
        <tr>
            <th>角色描述</th>
            <td>
                <textarea name="remark" class="form-control" rows="3" ><?php echo isset($info['remark'])?$info['remark']:'';?></textarea>
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