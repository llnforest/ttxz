

<!DOCTYPE html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8"/>
    <title><?php echo isset($navTabs['title'])?$navTabs['title']:'后台管理系统'?></title>

    {include file="publics:topCss"}
</head>
<body>
    <div class="container-fluid">
        <div id="alert"></div>
        [__REPLACE__]
    </div>

    {include file="publics:bottomJs"}
</body>
</html>




