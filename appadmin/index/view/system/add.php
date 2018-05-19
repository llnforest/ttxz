
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="{:url('system/index')}">企业列表</a></li>
        <li class="active"><a href="{:url('system/add')}">增加企业</a></li>
    </ul>
    <div class="site-signup">
        <div class="row">
                <form class="form-horizontal" action="{:url('system/add')}" method="post" >
                    {include file="form/form_system" /}
                </form>
            </div>
        </div>
    </div>
</div>
