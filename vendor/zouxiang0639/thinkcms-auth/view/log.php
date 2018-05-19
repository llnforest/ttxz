<?php require $pach . 'public/top.php';?>
<ul class="nav nav-tabs">
    <li class="active"><a href="{:Url('auth/log')}">日志列表</a></li>
</ul>
    <div>
        <div class="cf well form-search row">
            <form  method="get">
                <div class="fl">

                    <div class="btn-group">
                        <input name="user_name" class="form-control" value="{:input('username')}"  placeholder="姓名" type="text">
                    </div>
                    <div class="btn-group">
                        <input name="user_id" class="form-control" value="{:input('user_id')}" placeholder="用户ID" type="text">
                    </div>
                    <div class="btn-group">
                        <input name="title" class="form-control" value="{:input('title')}" placeholder="标题" type="text">
                    </div>
                    <div class="btn-group">
                        <input name="start" value="{:input('start')}" placeholder="起始日期" dom-class="date-start" class="date-time date-start form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="end" value="{:input('end')}" placeholder="截止日期" dom-class="date-end" class="date-time date-end form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">查询</button>
                    </div>
                    <div class="btn-group">
                        {if condition="checkPath('auth/clear')"}
                        <button type="button"  post-url="{:Url('auth/clear')}" post-msg="确定要清空日志记录吗？" class="btn btn-post btn-success">清空</button>
                        {/if}
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-hover table-bordered table-list" id="menus-table">
            <thead>
            <tr>
                <th width="100">ID</th>
                <th>标题</th>
                <th width="">用户</th>
                <th width="">执行地址</th>
                <th width="100">IP</th>
                <th width="150">执行时间</th>
                <th width="80">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $v) {?>
                <tr>
                    <td>{$v.id}</td>
                    <td>{$v.title}</td>
                    <td>{$v.username}</td>
                    <td>{$v.log_url}</td>
                    <td>{$v.action_ip}</td>
                    <td>{$v.create_time}</td>
                    <td>
                        {if condition="checkPath('auth/viewlog',['id'=>$v['id']])"}
                            <a href="{:url('auth/viewlog',['id'=>$v['id']])}">详细</a>
                        {/if}
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        {$page}
    </div>
<?php require $pach . 'public/foot.php';?>