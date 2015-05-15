$(function(){
   $("#commentButton").click(function(){
        createComment();
   });
   
   $("#comment").keypress(function(e){
        if(e.which == 13){
            if($("#post").val() != ""){
                createComment();
            }
        }
    });
    
    
    function createComment(){
	    var $comment = $("#comment").val();
        var $name = $("#guestName").val();
        var $userID = $("#sidebar").attr("user_id");
        if ($userID == "undefined") {
            $userID = 0;
        }
        
        var $storyID = $("#currentStory").attr("story_id");
                
        if ($name == "") {
            alert("You must enter a guest name, or register as a Vancity Social user.");
        }else if ($name == undefined) {
            $name = "";
        }
        
        $response = 0;
        
        $.post("functions/createNewsComment.php", {storyID:$storyID, comment:$comment, userID:$userID, guestName:$name, responseID:$response, thread: 0}, function(data){
            if(data != "false"){
                $("#comment").val("");
                if ($name == "" || $name == undefined) {
                    $name = $("#sidebar").attr("user_name");
                }
                $newContent = "<div class='comment'><p><strong>"+$name+"</strong> commented... " + $comment + " <em>just now</em></div>";
                $("#allComments").prepend($newContent);
                
                
                $newContent = '<div class="comment" comment_id="'+ data +'" thread_count="0"><div class="picMask">';
	            if ($userID == 0) {
	               $newContent += '<img src="http://vancitysocial.ca/images/noProfile.jpg"></div>';
	               $newContent += '<p><strong>' + $name + '</strong> '+ $comment +' <em>Just Now</em></p>';
	            }else{
	               $newContent += '<img src="' + $("#profilePic").attr("src") + '"/></div>';
	               $newContent += '<p><strong>' + $("#userBlock h2").text() + '</strong> '+ $comment +' <em>Just Now</em></p>';
	            }
	            //$newContent += '<div class="replyLine"><span class="respondComment">Respond to Comment</span></div><div class="responseBlock" style="display: none;">';
	            //$newContent += '<br>Guest Name:<br><input type="text" class="responseGuestName"><textarea name="comment" class="response" cols="25" rows="5"></textarea>';
	            //$newContent += '   <div class="responseButton">Comment</div></div>';
	            $newContent += "</div>";
	            
	            $("#newsComments").prepend($newContent);
	            $thisBlock.hide();
	            $("#comment").val("");
                
            }else{
                alert("technical difficulties, try posting again later");
            }
        });
    }
   
   
   
   /* reply to comment button */
   $("#newsComments").on('click', '.respondComment', function(){
   //$(".respondComment").click(function(){
      var $root = $(this).parent().parent();
      var state = 0;
      if ($root.find(".responseBlock").css("display") == "none") {
         state = 1;
      }
      $(".responseBlock").hide();
      $(".response").val("");
      $(".responseGuestName").val("");
      $(".respondComment").html("Respond to Comment");
      
      if (state == 1) {
         $root.find(".responseBlock").show();
         $(this).html("Hide Comment Box");
      }else{
         $root.find(".responseBlock").hide();
      }
      
      var $userID = $("#sidebar").attr("user_id");
   });
   
   //$(".responseButton").click(function(){
   $("#newsComments").on('click', '.responseButton', function(){
      var $root = $(this).parent().parent();
      var $commentID = $root.attr("comment_id");
      var $threadCount = $root.attr("thread_count");
      var $newThreadCount = parseInt($threadCount) + 1;
      var $storyID = $("#currentStory").attr("story_id");
      var $text = $root.find(".response").val();
      var $guestName = $root.find(".responseGuestName").val();
      var $userID = $("#sidebar").attr("user_id");
      if ($userID == "") {
         $userID = 0;
      }
      if (! $guestName) {
         $guestName = "";
      }
      var $response = $commentID;
      var $thisBlock = $(this).parent();
      
      
      
      $.post("functions/createNewsComment.php", {storyID:$storyID, comment:$text, userID:$userID, guestName:$guestName, responseID:$response, thread:$newThreadCount}, function(data){
         if(data != "false"){
            $(".response").val("");
            
            $newContent = '<div class="comment threaded indent'+ $newThreadCount +'" comment_id="'+ data +'" thread_count="'+ $newThreadCount + '"><div class="picMask">';
            if ($userID == 0) {
               $newContent += '<img src="http://vancitysocial.ca/images/noProfile.jpg"></div>';
               $newContent += '<p><strong>' + $guestName + '</strong> <span class="mainText">'+ $text +'</span> <em>Just Now</em></p>';
            }else{
               $newContent += '<img src="' + $("#profilePic").attr("src") + '"/></div>';
               $newContent += '<p><strong>' + $("#userBlock h2").text() + '</strong> '+ $text +' <em>Just Now</em></p>';
            }
            //$newContent += '<div class="replyLine"><span class="respondComment">Respond to Comment</span></div><div class="responseBlock" style="display: none;">';
            //$newContent += '<br>Guest Name:<br><input type="text" class="responseGuestName"><textarea name="comment" class="response" cols="25" rows="5"></textarea>';
            //$newContent += '   <div class="responseButton">Comment</div></div>';
            $newContent += "</div>";
            
            $root.after($newContent);
            $thisBlock.hide();
            $(".respondComment").html("Respond to Comment");
            
         }else{
            alert("technical difficulties, try posting again later");
         }
      });
       
   });
   
   
   
   
   
   
   
   
   /* send user message button */
   
   $(".messageUser").click(function(){
      $("#messageUserMask").show();
      var passiveUser = $(this).parents(".comment").attr("user_id");
      $("#messageUserBox").attr("passive_user", passiveUser);
      $("#submitDirect").show();
      $("#messsageAlert").hide();
            
      var thisComment = $(this).parent().parent().find(".mainText").text();
      $("#message").text("In response to '"+thisComment+"'... ");
   });
   
   $("#messageClose").click(function(){
   	  $("#messageAlert").hide();
   	  $("#submitDirect").show();
      $("#message").val("");
      $("#messageUserMask").hide();
   });
   
   $("#messageButton").click(function(){
      $thisMess = $("#message").val();
      var pUser = $(this).parents("#messageUserBox").attr("passive_user");
      var aUser = $("#sidebar").attr("user_id");
            
      $.post("functions/directMessage.php", {passive: pUser, active: aUser, message:$thisMess}, function(data){
	      if(data == "true"){
		      $("#messageAlert").show();
		      $("#submitDirect").hide();
	      }
      });
      
   });
   
});