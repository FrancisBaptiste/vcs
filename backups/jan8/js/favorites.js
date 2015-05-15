$(function(){
    $("body").on("click", ".addFav", function() {
    //$(".addFav").click(function(){
        $line = $(this).parent();
        $topicId = $(this).attr('topicId');
        $user = $("#sidebar").attr("user_id");
        $topicName = $(this).parent().find("a").html();
        $.post("functions/addFavorite.php", {topic:$topicId, user:$user}, function(data){
            if (data == "TRUE") {
                //$line.detach();
                //$line.find(".addFav").toggleClass("addFav removeFav");
                //$line.find(".removeFav").html
                $listItem = "<li><a href='http://vancitysocial.ca/app.php?i="+ $topicId +"'>" + $topicName + "</a> <span class='removeFav' topicId='"+$topicId+"'>[-]</span></li>";
                //$("#userTopics").append($line);
                $line.remove();
                $("#userTopics").append($listItem);
            }else{alert("something is broken. try again later.")}
        });
    });
    
    $("body").on("click", ".removeFav", function(){
    //$(".removeFav").click(function(){
        $line = $(this).parent();
        $topicId = $(this).attr('topicId');
        $user = $("#sidebar").attr("user_id");
        $topicName = $(this).parent().find("a").html();
        $.post("functions/removeFavorite.php", {topic:$topicId, user:$user}, function(data){
            if (data == "TRUE") {
                //$line.detach();
                //$line.find(".removeFav").toggleClass("removeFav addFav");
                //$line.find(".removeFav").html("[+]");
                $listItemAdd = "<li><a href='http://vancitysocial.ca/app.php?i="+ $topicId +"'>" + $topicName + "</a> <span class='addFav' topicId='"+$topicId+"'>[+]</span></li>";
                //$("#allTopics").append($line);
                $line.remove();
                $("#allTopics").append($listItemAdd);
            }else{alert("something is broken. try again later.")}
        });
    });
});