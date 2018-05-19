<!DOCTYPE html>
<head>
    <title>后台管理系统</title>
    <meta charset="UTF-8"/>
    <link href="__PublicAdmin__/css/theme.min.css" rel="stylesheet">
    <link href="__PublicDefault__/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="__PublicAdmin__/css/index.min.css">
    <link href="__PublicAdmin__/css/common.css" rel="stylesheet">
</head>


<body style="min-width:900px;" screen_capture_injected="true">
[__REPLACE__]
<!--<audio id="getNewOrder">-->
<!--    <source = src="__PublicAdmin__/music/order.mp3" type="audio/mp3">-->
<!--</audio>-->
<script src="__PublicAdmin__/js/jquery.min.js"></script>
<script src="__PublicAdmin__/js/bootstrap.min.js"></script>
<script src="__PublicAdmin__/js/jquery-1.8.0.min.js"></script>
<script src="__PublicAdmin__/js/index.js"></script>
<script>
    var ismenumin = $("#sidebar").hasClass("menu-min");
    $(".nav-list").on( "click",function(event) {
        var closest_a = $(event.target).closest("a");
        if (!closest_a || closest_a.length == 0) {
            return
        }
        if (!closest_a.hasClass("dropdown-toggle")) {
            if (ismenumin && "click" == "tap" && closest_a.get(0).parentNode.parentNode == this) {
                var closest_a_menu_text = closest_a.find(".menu-text").get(0);
                if (event.target != closest_a_menu_text && !$.contains(closest_a_menu_text, event.target)) {
                    return false
                }
            }
            return
        }
        var closest_a_next = closest_a.next().get(0);
        if (!$(closest_a_next).is(":visible")) {
            var closest_ul = $(closest_a_next.parentNode).closest("ul");
            if (ismenumin && closest_ul.hasClass("nav-list")) {
                return
            }
            closest_ul.find("> .open > .submenu").each(function() {
                if (this != closest_a_next && !$(this.parentNode).hasClass("active")) {
                    $(this).slideUp(150).parent().removeClass("open")
                }
            });
        }
        if (ismenumin && $(closest_a_next.parentNode.parentNode).hasClass("nav-list")) {
            return false;
        }
        $(closest_a_next).slideToggle(150).parent().toggleClass("open");
        return false;
    });
    {if condition="checkPath('reserve/order/index')"}
    var orderaudio = document.getElementById("getNewOrder");
    function getNewOrder(){
        $.ajax(
            {
                url : '<?=url("index/getNewOrder")?>',
                type : 'post',
                dataType : 'json',
                success : function (json)
                {
                    if(json.code == 1)
                        orderaudio.play();
                }
            });
        // audio.play();
    }
//    window.setInterval(getNewOrder, 60000);
    {/if}
</script>

</body>
</html>







