<ul class="nav nav-tabs">
    {if condition="checkPath('banner/index')"}
    <li><a href="{:Url('banner/index')}">banner列表</a></li>
    {/if}
    {if condition="checkPath('banner/bannerAdd')"}
    <li class="active"><a href="{:Url('banner/bannerAdd')}">添加banner</a></li>
    {/if}
    
</ul>
 <form  class="form-horizontal" action="{:url('banner/bannerAdd')}" method="post">
    {include file="form:form_banner" /}
</form>
