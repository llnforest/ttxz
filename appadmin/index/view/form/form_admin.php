
<link rel="stylesheet" href="__PublicDefault__/multiselect/css/multi-select.css?20171208">
<script src="__PublicDefault__/multiselect/js/jquery.multi-select.js"></script>
<script src="__PublicDefault__/multiselect/js/joinable.js"></script>
<div class="col-sm-12">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>账号</th>
            <td>
                {if(!isset($info.name))}
                <input class="form-control text" type="text" name="name"  value="{$info.name??''}" placeholder="账号">
                <span class="form-required">*</span>
                {else}
                <input class="form-control text" type="text" readonly value="{$info.name??''}">
                {/if}
            </td>
        </tr>
        <tr>
            <th>密码</th>
            <td>
                <input class="form-control text" type="text" name="password" value="" placeholder="密码">
                <span class="form-required">*<span style="font-size:12px;position:relative;top:-5px;">{$info.name?="填写则修改密码，不填则保持原密码"}</span> </span>
            </td>
        </tr>
        <tr>
            <th>姓名</th>
            <td>
                <input class="form-control text" type="text" name="nick_name" value="{$info.nick_name??''}" placeholder="姓名">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>手机</th>
            <td>
                <input class="form-control text" type="text" name="phone" value="{$info.phone??''}" placeholder="手机">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>邮箱</th>
            <td>
                <input class="form-control text" type="text" name="email" value="{$info.email??''}" placeholder="邮箱">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>角色</th>
            <td>
                <select class="form-control" multiple="multiple" id="multi-select" name="role[]">
                    {$info.role ?? ''}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="text-center">
                <button type="button" class="btn btn-success form-post " >保存</button>
                <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(function(){
        $("#multi-select").multiSelect({
            afterInit: function()
            {
                // Add alternative scrollbar to list
                this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
            },
            afterSelect: function()
            {
                // Update scrollbar size
                this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
            }
        });
    })
</script>

