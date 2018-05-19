
<div class="wrap">
    <ul class="nav nav-tabs">
        {if condition="checkPath('admin/index')"}
        <li><a href="{:url('admin/index')}">用户管理</a></li>
        {/if}
        {if condition="checkPath('admin/add')"}
        <li class="active"><a href="{:url('admin/add')}">增加用户</a></li>
        {/if}
    </ul>
    <div class="site-signup">
        <div class="row">
                <form class="form-horizontal" action="{:url('admin/add')}" method="post" >
                    {include file="form/form_admin" /}
                </form>
            </div>
        </div>
    </div>
</div>
