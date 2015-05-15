$(function(){
    $(".notification").click(function(){
        $toFetch = $(this).attr("data-fetch-pid");
        $nid = $(this).attr("data-nid");
        $thisNote = $(this);
        $.post("functions/readNotification.php", {nid:$nid}, function(data){
            if (data == "TRUE") {
                $thisNote.remove();
                //$("#oldNotificationsList").prepend($thisNote);
                //if there are no more notifications, hide the header too
                if ($(".notification").length == 0) {
                    $("#notificationsMessage").fadeOut();
                }
            }
        });
        $.post("functions/getPost.php", {pid: $toFetch}, function(data){
            $("#commentSubmit").remove();
            $("#notificationBox").html(data);
            $("#notificationMask").show();
        })
        .fail(function() { alert("something didn't work"); });
    });
    
    $("body").on("click", "#notification-close", function(){
        $("#notificationMask").hide();
    });
    
    // have to add edit this event listener up top, so it works for the notification at the top of the newsfeed.
    
    
    /*
    $("#oldNotifications").click(function(){
        if ($(this).hasClass("open")) {
            $(this).removeClass("open");
            $("#oldNotificationsList").slideUp();
            $(this).html("old notifications [+]");
        }else{
            $(this).addClass("open");
            $("#oldNotificationsList").slideDown();
            $(this).html("old notifications [-]");
        }
    });*/
    
    $(".notificationPageItem").click(function(){
        $toFetch = $(this).attr("data-fetch-pid");
        $.post("functions/getPost.php", {pid: $toFetch}, function(data){
            $("#notificationBox").html(data);
            $("#notificationMask").show();
        })
        .fail(function() { alert("something didn't work"); });
    });
});