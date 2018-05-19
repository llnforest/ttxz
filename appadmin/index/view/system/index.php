<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li  class="active"><a href="{:url('system/index')}">企业列表</a></li>
        <li><a href="{:url('system/add')}">增加企业</a></li>
    </ul>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>企业名称</th>
            <th>企业简称</th>
            <th>联系方式</th>
            <th>状态</th>
        </tr>

        </thead>
        <tbody>

        <?php foreach($list as $v) {
            ?>
            <tr>
                <td>{$v.id}</td>
                <td>{$v.name}</td>
                <td>{$v.code}</td>
                <td>{$v.phone}</td>
                <td class="layui-form">
                    <input type="checkbox" data-name="status" data-url="{:url('system/status',['id'=>$v.id])}" lay-skin="switch" lay-text="开启|禁用" <?php echo $v['status']!==0?'checked':''?> data-value="1|0">
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="text-center">
        {$page}
    </div>
</div>
