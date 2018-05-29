
<style>
    body {
        background-color: #c3cdda;
        background: url(__PublicAdmin__/images/login_bg.png) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        overflow:hidden;
    }
    .m-login { width: 400px;height: 260px;padding: 30px;position: absolute;left: 0;margin: auto;top: 0;bottom: 0;right: 0;border-radius: 5px;border: 2px solid #009688;}
    .m-login .login-head { height:40px;line-height:40px; padding-left:10px; font-size:24px;  text-align:center; color:#009688;margin-bottom:10px; font-weight: 700;}
    .layui-form-item input:focus{
        border-style:solid;
        border-color: #009688;
        box-shadow: 0 0 30px #009688;
        outline: #009688;
    }
    .m-login .logo{margin-right:10px;}
    canvas{width:100%;}
</style>
<div id="app"></div>
<div class="m-login">
    <div class="login-head"><img class="logo" src="__PublicAdmin__/images/logo.png">恒通科技管理系统</div>
    <form id="login-form" class="layui-form" action="{:Url('publics/login')}" method="post" role="form">
            <div class="layui-form-item field-loginform-username required">
                <input type="text" class="layui-input" name="name" maxlength="256" placeholder="用户名" value="">
            </div>
            <div class="layui-form-item field-loginform-password required">
                <input type="password" id="loginform-password" class="layui-input" name="login_password" maxlength="256" placeholder="密码">
            </div>

            <div class="layui-form-item">
                <button type="submit" class="layui-btn layui-btn-fluid u-login-btn">登录</button>
            </div>
    </form>

</div>
<script src="__PublicAdmin__/js/plugins/login_canvas_1.js?{$Think.config.version_time}"></script>
<script src="__PublicAdmin__/js/plugins/login_canvas_2.js?{$Think.config.version_time}"></script>