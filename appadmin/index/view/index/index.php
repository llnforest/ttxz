
<div id="loading"><i class="loadingicon"></i><span>正在加载...</span></div>
<div id="right_tools_wrapper">
   <span id="right_tools_clearcache" title="清除缓存" onclick="javascript:openapp('<?php echo Url('publics/clear')?>','index_clearcache','清除缓存');"><i class="fa fa-trash-o right_tool_icon"></i></span>
    <span id="refresh_wrapper" title="REFRESH_CURRENT_PAGE" ><i class="fa fa-refresh right_tool_icon"></i></span> </div>
<!--head-->

<div class="navbar">
    <div class="navbar-inner">

        <div class="container-fluid" >

            <a href="{:url('index/index')}" class="brand">
                <small>后台管理中心 </small>
            </a>
            <div class="pull-left nav_shortcuts" >
            </div>
            <!--顶部-->
            <ul id="myTabs" class="nav simplewind-nav pull-lift" style="margin-left: 48px;">
                {$menu.menuName}
            </ul>

            <ul class="nav simplewind-nav pull-right">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" width="22" height="22" src="__PublicAdmin__/images/admin_logo.png" alt="admin">
                        <span class="user-info"> {$username}，欢迎您 </span>
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                        <li>
                            <a href="javascript:openapp('<?php echo Url('admin/password')?>','admin_password','修改密码');">
                                <i class="fa fa-user"></i> 修改密码
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo Url('publics/logout')?>" data-method="post">
                                <i class="fa fa-sign-out"></i> 退出</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--head-->

<!--content-->
<div class="main-container container-fluid">
    <!--左下部-->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <a class="btn btn-small btn-warning" href="{:url('index/index')}" title="网站首页" >
                <i class="fa fa-home"></i>
            </a>

            <a class="btn btn-small btn-danger" href="javascript:openapp('<?php echo Url('publics/clear')?>','index_clearcache','清除缓存');" title="清除缓存">
                <i class="fa fa-trash-o"></i>
            </a>
        </div>
        <div class="nav_wraper">
            {$menu.menuHtml}
        </div>
    </div>

    <!--右下部-->
    <div class="main-content">
        <!--操作记录区-->
        <div class="breadcrumbs" id="breadcrumbs">
            <a id="task-pre" class="task-changebt">←</a>
            <div id="task-content">
                <ul class="macro-component-tab" id="task-content-inner">
                    <li class="macro-component-tabitem noclose" app-id="0" app-url="<?php echo Url('index/home')?>" app-name="首页"> <span class="macro-tabs-item-text">首页</span> </li>
                </ul>
                <div class="cf"></div>
            </div>
            <a id="task-next" class="task-changebt">→</a>
        </div>
        <!--内容区-->
        <div class="page-content" id="content">
            <iframe src="<?php echo Url('index/home')?>" style="width:100%;height: 100%;" frameborder="0" id="appiframe-0" class="appiframe"></iframe>
        </div>
    </div>
</div>