<ul class="nav nav-tabs">
    {if condition="checkPath('joinRecord/index')"}
    <li class="active"><a href="{:Url('joinRecord/index')}">分享记录</a></li>
    {/if}
</ul>
 <div>
        <div class="cf well form-search row">
            <form  method="get">
                <div class="fl">
                    <div class="btn-group">
                        <input name="nickname" value="{:input('nickname')}" placeholder="微信昵称" class="form-control"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="phone" value="{:input('phone')}" placeholder="手机号" class="form-control"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="start" value="{:input('start')}" placeholder="分享起始日期" readonly dom-class="date-start" class="date-time date-start form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="end" value="{:input('end')}" placeholder="分享结束日期" readonly dom-class="date-end" class="date-time date-end form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">查询</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-hover table-bordered table-list" id="menus-table">
            <thead>
            <tr>
                <th width="80">昵称</th>
                <th width="80">手机号</th>
                <th width="120">分享时间<span order="a.create_time" class="order-sort"> </span></th>
            </tr>
            </thead>
            <tbody>
            {foreach $list as $v}
                <tr>
                    <td>{$v.nickname}</td>
                    <td>{$v.phone}</td>
                    <td>
                        {$v.create_time}
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <div class="text-center">
        {$page}
    </div>