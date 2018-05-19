<ul class="nav nav-tabs">
    {if condition="checkPath('prize/index')"}
    <li class="active"><a href="{:Url('prize/index')}">奖品列表</a></li>
    {/if}
    {if condition="checkPath('prize/prizeAdd')"}
    <li><a href="{:Url('prize/prizeAdd')}">添加奖品</a></li>
    {/if}
</ul>
 <div>
        <div class="cf well form-search row">

            <form  method="get">
                <div class="fl">
                    <div class="btn-group layui-form">
                        <select name="status" class="form-control">
                            <option value="">全部状态</option>
                            <option value="1" {if input('status') == 1}selected{/if}>正常</option>
                            <option value="0" {if input('status') === '0'}selected{/if}>停用</option>
                        </select>
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
                <th width="100">奖品名称</th>
                <th width="80" >中奖概率</th>
                <th width="80" >排序<span order="sort" class="order-sort"> </span></th>
                <th width="80">状态<span order="status" class="order-sort"> </span></th>
                <th width="80">操作</th>
            </tr>
            </thead>
            <tbody>
            {foreach $list as $v}
                <tr>
                    <td>{$v.name}</td>
                    <td>
                        {$v.rate}%
                    </td>
                    <td>
                        {if condition="checkPath('prize/inputPrize',['id'=>$v['id']])"}
                        <input class="form-control change-data short-input"  post-id="{$v.id}" post-url="{:url('prize/inputPrize')}" data-name="sort" value="{$v.sort}">
                        {else}
                        {$v.sort}
                        {/if}
                    </td>
                    <td class="layui-form">
                        {if condition="checkPath('prize/switchPrize',['id'=>$v.id])"}
                        <input type="checkbox" data-name="status" data-url="{:url('prize/switchPrize',['id'=>$v.id])}" lay-skin="switch" lay-text="正常|停用" {$v.status == 1 ?'checked':''} data-value="1|0">
                        {else}
                        {$v.status == 1?'<span class="blue">正常</span>':'<span class="red">停用</span>'}
                        {/if}
                    </td>
                    <td>
                        {if condition="checkPath('prize/prizeEdit',['id'=>$v['id']])"}
                        <a  href="{:url('prize/prizeEdit',['id'=>$v['id']])}">编辑</a>
                        {/if}
                        {if condition="checkPath('prize/prizeDelete',['id'=>$v['id']])"}
                            <a  class="span-post" post-msg="确定要删除吗" post-url="{:url('prize/prizeDelete',['id'=>$v['id']])}">删除</a>
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