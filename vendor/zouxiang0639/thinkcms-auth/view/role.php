
<?php require $pach . 'public/top.php';?>

    <ul class="nav nav-tabs">

        <li class="active"><a href="<?php echo Url('auth/role')?>">角色管理</a></li>
        {if condition="checkPath('auth/roleAdd')"}
            <li><a href="<?php echo Url('auth/roleAdd')?>">增加角色</a></li>
        {/if}

    </ul>
    <table class="table table-hover table-bordered">
        <thead>
        <tr>
            <th width="30">ID</th>
            <th align="left">角色名称</th>
            <th align="left">角色描述</th>
            <th width="50" align="left">状态</th>
            <th width="160">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($list as $v){?>
            <tr>
                <td>{$v.id}</td>
                <td>{$v.name}</td>
                <td>{$v.remark}</td>
                <td  class="layui-form">
                    {if $v.id == 1}
                    <span class="blue">√</span>
                    {else}
                    {if condition="checkPath('auth/roleStatus')"}
                    <input type="checkbox" data-name="status" data-url="{:url('auth/roleStatus',['id'=>$v.id])}" lay-skin="switch" lay-text="开启|禁用" <?php echo $v['status']!==0?'checked':''?> data-value="1|0">
                    {else}
                    <?php if($v['status']==1){ ?>
                        <span color="blue">√</span>
                    <?php }else{ ?>
                        <span color="red">╳</span>
                    <?php } ?>
                    {/if}
                    {/if}
                </td>
                <td>
                    <?php if($v['id']==1){ ?>
                        <span class="grey">权限设置</span>
                        <span class="grey">编辑</span>
                        <span class="grey">删除</span>
                    <?php }else{ ?>
                        {if condition="checkPath('auth/authorize',['id'=>$v['id']])"}
                            <a href="<?php echo Url('auth/authorize',['id'=>$v['id']])?>">权限设置</a>
                        {/if}
                        {if condition="checkPath('auth/roleEdit',['id'=>$v['id']])"}
                            <a href="<?php echo Url('auth/roleEdit',['id'=>$v['id']])?>">编辑</a>
                        {/if}
                        {if condition="checkPath('auth/roleDelete',['id'=>$v['id']])"}
                            <span  class="span-post" post-msg="你确定要删除吗" post-url="<?php echo Url('auth/roleDelete',['id'=>$v['id']])?>">删除</span>
                        {/if}
                    <?php } ?>

                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php require $pach . 'public/foot.php';?>




