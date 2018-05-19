
<div class="col-sm-12">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th>奖品名称</th>
                <td>
                    <input class="form-control text" type="text" name="name" value="{$info.name??''}" placeholder="奖品名称">
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th>中奖概率</th>
                <td>
                    <input class="form-control text" type="text" name="rate" value="{$info.rate??''}" placeholder="中奖概率">
                    <span class="input-text">%</span>
                    <span class="form-required">*</span>
                </td>
            </tr>
            <tr>
                <th>奖品状态</th>
                <td class="layui-form">
                    <input type="checkbox" data-name="status" lay-skin="switch" lay-text="启用|停用" {if !isset($info.updown) || $info.updown == 1}checked{/if} data-value="1|0">
                </td>
            </tr>
            <tr>
                <th>奖品排序</th>
                <td>
                    <input class="form-control text" type="text" name="sort" value="{$info.sort??''}" placeholder="奖品排序">
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

