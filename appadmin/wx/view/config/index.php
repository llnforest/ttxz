<ul class="nav nav-tabs">
    {if condition="checkPath('config/index')"}
    <li class="active"><a href="{:Url('config/index')}">小程序配置</a></li>
    {/if}
</ul>
 <form  class="form-horizontal" action="{:url('config/index',['id'=>$info.id])}" method="post">
    {include file="form:form_config" /}
</form>
