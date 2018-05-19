<ul class="nav nav-tabs">
    {if condition="checkPath('joinRecord/index')"}
    <li class="active"><a href="{:Url('joinRecord/index')}">抽奖记录</a></li>
    {/if}
</ul>
 <div>
        <div class="cf well form-search row">
            <form  method="get">
                <div class="fl">
                    <div class="btn-group layui-form">
                        <select name="status" class="form-control">
                            <option value="">全部状态</option>
                            <option value="0" {if input('status') === '0'}selected{/if}>未中奖</option>
                            <option value="1" {if input('status') == 1}selected{/if}>已中奖</option>
                            <option value="2" {if input('status') == 2}selected{/if}>已派奖</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <input name="nickname" value="{:input('nickname')}" placeholder="微信昵称" class="form-control"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="phone" value="{:input('phone')}" placeholder="手机号" class="form-control"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="start" value="{:input('start')}" placeholder="中奖起始日期" readonly dom-class="date-start" class="date-time date-start form-control laydate-icon"  type="text">
                    </div>
                    <div class="btn-group">
                        <input name="end" value="{:input('end')}" placeholder="中奖结束日期" readonly dom-class="date-end" class="date-time date-end form-control laydate-icon"  type="text">
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
                <th width="70">状态<span order="a.status" class="order-sort"> </span></th>
                <th width="120">中奖时间<span order="a.create_time" class="order-sort"> </span></th>
            </tr>
            </thead>
            <tbody>
            {foreach $list as $v}
                <tr>
                    <td>{$v.nickname}</td>
                    <td>{$v.phone}</td>
                    <td><span class="{$v.status|str_replace=[0,1,2],['red','blue','green'],###}">{$v.status|str_replace=[0,1,2],['未中奖','已中奖','已派奖'],###}</span></td>
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