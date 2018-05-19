
<div class="wrap">
    <ul class="nav nav-tabs">
        <li class="active"><a href="">修改密码</a></li>
    </ul>
    <div class="site-signup">
        <div class="row">
            <form class="form-horizontal" action="{:url('admin/password')}" method="post" >
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>原密码</th>
                            <td>
                                <input class="form-control text" type="text" name="old_password" {$info.name?="readonly"} value="">
                                <span class="form-required">*</span>
                            </td>
                        </tr>
                        <tr>
                            <th>新密码</th>
                            <td>
                                <input class="form-control text" type="text" name="password" value="">
                                <span class="form-required">* </span>
                            </td>
                        </tr>
                        <tr>
                            <th>确认密码</th>
                            <td>
                                <input class="form-control text" type="text" name="c_password" value="">
                                <span class="form-required">*</span>
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
            </form>
        </div>
    </div>
</div>
