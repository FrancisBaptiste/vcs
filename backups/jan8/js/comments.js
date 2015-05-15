$(function(){
    //$(".addComment").click(function(){
	$("#main").on("click", ".addComment", function(){
    	$userID = $("#sidebar").attr("user_id");
    	if($userID != ""){
	    	$thisPostID = $(this).parent().attr("post_id");
	        
	        $("#commentSubmit").remove();
	        $(".addComment").show();
	        $(this).hide();
	        
	        $commentBox = "<div id='commentSubmit'>";
	        $commentBox += "<textarea name='commentPost' id='commentPost' cols='20' rows='5'>leave a comment...</textarea>";
	        $commentBox += "<div id='commentPostButton'>Post Comment</div></div>";
	        
	        $(this).parent().before($commentBox);
    	}else{
	    	alert("Sorry. Only logged in users can comment.");
    	}
    });
    
    $("body").on("click", "#commentPostButton", function(){
        createComment();
    });

    $("body").on("keypress", "#commentPost", function(e){
        if(e.which == 13){
            if($("#commentPost").val() != ""){ // this used to be #post, I think that was a mistake -Fran
                createComment();
            }
        }
    });
    
    $("body").on("focus", "#commentPost", function(){
		if ($(this).val() == "leave a comment...") {
			$(this).val("");
		}
    });
    
    $("#main").on("click", ".viewAllComments", function(){
	    $(this).parent().find(".hiddenComment").slideDown().removeClass("hiddenComment");
	    $(this).remove();
    });
    
    
    function createComment(){
        $val = $("#commentPost").val();
        $user = $("#sidebar").attr("user_id");
        $name = $("#sidebar").attr("user_name");
        $postImage = $("#sidebar").attr("user_pic");
        $pid = $("#commentSubmit").parent().attr("post_id");
        $postUserId = $("#commentSubmit").parent().attr("user_id");
        $.post("functions/addComment.php", {user: $user, post: $val, pid: $pid, uid:$postUserId}, function(data){
            if(data == "true"){
                $("#commentPost").val("");
                $newContent = "<div class='comment'><div class='picMask'>"+$postImage+"</div><p><strong>"+$name+"</strong> commented... " + $val + " <em>just now</em></div>";
                $("#commentSubmit").prepend($newContent);
            }else{
            	alert(data);
                //alert("technical difficulties, try posting again later");
            }
        });
    }
});