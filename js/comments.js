$(function(){
    //$(".addComment").click(function(){
	$("#main").on("click", ".addComment", function(){
    	$userID = $("#sidebar").attr("data-user-id");
    	if($userID != ""){
	    	$thisPostID = $(this).parent().attr("data-post-id");
	        
	        $("#commentSubmit").remove();
	        $(".addComment").show();
	        $(this).hide();
	        
	        $commentBox = "<div id='commentSubmit'>";
	        $commentBox += "<textarea name='commentPost' id='commentPost' cols='20' rows='5'></textarea>";
	        $commentBox += "<div id='commentPostButton'>Post Comment</div></div>";
	        
	        $(this).after($commentBox);
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
        $user = $("#sidebar").attr("data-user-id");
        $name = $("#sidebar").attr("data-user-name");
        $postImage = $("#sidebar").attr("data-user-pic");
        $pid = $("#commentSubmit").parent().attr("data-post-id");
        $postUserId = $("#commentSubmit").parent().attr("data-user-id");
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