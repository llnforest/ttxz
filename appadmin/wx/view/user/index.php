<ul class="nav nav-tabs">
    {if condition="checkPath('user/index')"}
    <li class="active"><a href="{:Url('user/index')}">用户列表</a></li>
    {/if}
</ul>
 <div>
        <div class="cf well form-search row">

            <form  method="get" id="myForm">
                <div class="fl">
                    <div class="btn-group layui-form">
                        <select name="status" class="form-control">
                            <option value="">全部状态</option>
                            <option value="1" {if input('status') == 1}selected{/if}>正常</option>
                            <option value="0" {if input('status') === '0'}selected{/if}>禁用</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <input name="phone" value="{:input('phone')}" placeholder="手机号" class="form-control"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="nickname" value="{:input('nickname')}" placeholder="微信昵称" class="form-control"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="start" value="{:input('start')}" placeholder="注册起始日期" readonly dom-class="date-start" class="date-time date-start form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="end" value="{:input('end')}" placeholder="注册结束日期" readonly dom-class="date-end" class="date-time date-end form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">查询</button>
                        {if checkPath('user/download')}
                        <button type="button" class="btn btn-success download">下载</button>
                        {/if}
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-hover table-bordered table-list" id="menus-table">
            <thead>
            <tr>
                <th width="80">头像</th>
                <th width="80">昵称</th>
                <th width="80">手机号</th>
                <th width="80">抽奖次数<span order="join_times" class="order-sort"> </span></th>
                <th width="80">中奖次数<span order="win_times" class="order-sort"> </span></th>
                <th width="80">分享次数<span order="share_times" class="order-sort"> </span></th>
                <th width="50">性别</th>
                <th width="70">状态<span order="status" class="order-sort"> </span></th>
                <th width="120">注册时间<span order="create_time" class="order-sort"> </span></th>
                <th width="60">操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach $list as $v}
                <tr>
                    <td><img class="mini-image" src="{$v.headimgurl?$v.headimgurl:''}" style="width:60px"></td>
                    <td>{$v.nickname}</td>
                    <td>{$v.phone}</td>
                    <td>{$v.join_times}</td>
                    <td>{$v.win_times}</td>
                    <td>{$v.share_times}</td>
                    <td>{$v.sex|str_replace=[1,2],['男','女'],###}</td>
                    <td class="layui-form">
                        {if condition="checkPath('user/switchUser',['id'=>$v.id])"}
                        <input type="checkbox" data-name="status" data-url="{:url('user/switchUser',['id'=>$v.id])}" lay-skin="switch" lay-text="正常|禁用" {$v.status == 1 ?'checked':''} data-value="1|0">
                        {else}
                        {$v.status == 1?'<span class="blue">正常</span>':'<span class="red">禁用</span>'}
                        {/if}
                    </td>
                    <td>
                        {$v.create_time}
                    </td>
                    <td>
                        {if condition="checkPath('user/userDelete',['id'=>$v['id']])"}
                            <a  class="span-post" post-msg="确定要删除吗" post-url="{:url('user/userDelete',['id'=>$v['id']])}">删除</a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="text-center">
        {$page}
    </div>
<script>

    $(".download").click(function(){
        url = "{:url('user/download')}?" + $("#myForm").serialize();
//            "start="+getQueryString('start')+"&end="+getQueryString('end')+"&name="+getQueryString('name')+"&order_a_id="+getQueryString('order_a_id')+"&order_id="+getQueryString('order_id')+"&imei="+getQueryString('imei')+'&status=0';
        window.location.href = url;
    });

</script>