<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        {if condition="checkPath('admin/index')"}
        <li  class="active"><a href="{:url('admin/index')}">用户管理</a></li>
        {/if}
        {if condition="checkPath('admin/add')"}
        <li><a href="{:url('admin/add')}">增加用户</a></li>
        {/if}
    </ul>

    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="50">ID</th>
            <th>账号</th>
            <th>姓名</th>
            <th>邮箱</th>
            <th>最后登录IP</th>
            <th>最后登录时间</th>
            <th>状态</th>
            <th width="170">操作</th>
        </tr>

        </thead>
        <tbody>

        <?php foreach($list as $v) {
            ?>
            <tr>
                <td>{$v.id}</td>
                <td>{$v.name}</td>
                <td>{$v.nick_name}</td>
                <td>{$v.email}</td>
                <td>{$v.last_login_ip}</td>
                <td>{$v.last_login_time}</td>
                <td class="layui-form">
                    {if $v.id == 1}
                    <span class="blue">√</span>
                    {else}
                    {if condition="checkPath('admin/status')"}
                    <input type="checkbox" data-name="status" data-url="{:url('admin/status',['id'=>$v.id])}" lay-skin="switch" lay-text="开启|禁用" <?php echo $v['status']!==0?'checked':''?> data-value="1|0">
                    {else}
                    {$v.status == 1?'<span class="blue">√</span>':'<span class="red">╳</span>'}
                    {/if}
                    {/if}
                </td>
                <td>
                    {if $v.id == 1}
                    <span class="grey">独立权限</span>
                    <span class="grey">编辑</span>
                    <span class="grey">删除</span>
                    {else /}
                    {if condition="checkPath('auth/adminAuthorize',['id' => $v['id'],'name'=>$v['name']])"}
                        <a href="<?php echo Url('auth/adminAuthorize',['id' => $v['id'],'name'=>$v['name']])?>">独立权限</a>
                    {/if}
                    {if condition="checkPath('admin/edit',['id' => $v['id']])"}
                        <a href="<?php echo Url('admin/edit',['id' => $v['id']])?>">编辑</a>
                    {/if}
                    {if condition="checkPath('admin/delete',['id' => $v['id']])"}
                        <span class="span-post" post-msg="你确定要删除吗" post-url="<?php echo Url('admin/delete',['id' => $v['id']])?>">删除</span>
                    {/if}
                    {/if}
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <div class="text-center">
        {$page}
    </div>
</div>
