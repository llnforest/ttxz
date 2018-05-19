<ul class="nav nav-tabs">
    {if condition="checkPath('banner/index')"}
    <li><a href="{:Url('banner/index')}">banner列表</a></li>
    {/if}
    {if condition="checkPath('banner/bannerAdd')"}
    <li><a href="{:Url('banner/bannerAdd')}">添加banner</a></li>
    {/if}
    {if condition="checkPath('banner/bannerEdit',['id'=>$info.id])"}
    <li class="active"><a href="{:Url('banner/bannerEdit',['id'=>$info.id])}">修改banner</a></li>
    {/if}
</ul>
 <form  class="form-horizontal" action="{:url('banner/bannerEdit',['id'=>$info.id])}" method="post">
    {include file="form:form_banner" /}
</form>
