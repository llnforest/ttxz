<ul class="nav nav-tabs">
    {if condition="checkPath('prize/index')"}
    <li><a href="{:Url('prize/index')}">奖品列表</a></li>
    {/if}
    {if condition="checkPath('prize/prizeAdd')"}
    <li><a href="{:Url('prize/prizeAdd')}">添加奖品</a></li>
    {/if}
    {if condition="checkPath('prize/prizeEdit',['id'=>$info.id])"}
    <li class="active"><a href="{:Url('prize/prizeEdit',['id'=>$info.id])}">修改奖品</a></li>
    {/if}
</ul>
 <form  class="form-horizontal" action="{:url('prize/prizeEdit',['id'=>$info.id])}" method="post">
    {include file="form:form_prize" /}
</form>
