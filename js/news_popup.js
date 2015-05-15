$(function(){
	var $userID = $("#sidebar").attr("data-user-id");
	
   $("#newsBox").on("click", "#commentButton", function(){
      if ($userID != "") {
        createComment();
      }else{
         alert("you need to be signed in to leave a comment");
      }
   });
   
   //$("#comment").keypress(function(e){
   $("#newsBox").on("keypress", "#comment", function(e){
        if(e.which == 13){
            if($("#post").val() != ""){
                if ($userID != "") {
                  createComment();
                }else{
                   alert("you need to be signed in to leave a comment");
                }
            }
        }
    });
    
    
    function createComment(){
	    var $comment = $("#comment").val();
        var $name = $("#guestName").val();
        var $userID = $("#sidebar").attr("data-user-id");
        if ($userID == "undefined") {
            $userID = 0;
        }
        
        var $storyID = $("#currentStory").attr("data-story-id");
                
        if ($name == "") {
            alert("You must enter a guest name, or register as a Vancity Social user.");
        }else if ($name == undefined) {
            $name = "";
        }
        
        $response = 0;
        
        $.post("functions/createNewsComment.php", {storyID:$storyID, comment:$comment, userID:$userID, guestName:$name, responseID:$response, thread: 0}, function(data){
            if(data != "false"){
               
                $("#comment").val("");
                
                var $thisTitle = $("#currentStory h2").text();
                
                
               var newPost = '<div class="post" data-user-id="">';
               newPost += "<div class='topicTag'><span><a href='app.php?i=41'>news comments</a></span></div>";
               newPost +=   '<div class="picMask"><img src="' + $("#profilePic").attr("src") + '"></div>';
               newPost +=   '<div>';
               
               newPost += '<p><strong class="userRollover" data-user-id="">'+$("#userBlock h2").text().replace("(edit)", "")+'</strong> <span class="mainText"><span class="newsPost">commented on the story <a href="http://vancitysocial.ca/news.php?story='+$storyID+'">'+$thisTitle+'</a>...</span> '+$comment+'</span></p>';
               newPost +=  '</div><div class="breaker"></div></div>';
               
               $("#posts").prepend(newPost);
               
               var successSubmit = "<p>You're comment, <em>&ldquo;" + $comment + "&rdquo;</em> has been posted to the main timeline.";
               
               $("#newsCommentSubmit").html(successSubmit);
                
            }else{
                alert("technical difficulties, try posting again later");
            }
        });
    }
   
   
   
   /* reply to comment button */
   $("#newsBox").on('click', '.respondComment', function(){
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
      
      var $userID = $("#sidebar").attr("data-user-id");
   });
   
   //$(".responseButton").click(function(){
   /* I don't think this is doing anything anymore
	   
   $("#newsBox").on('click', '.responseButton', function(){
      var $root = $(this).parent().parent();
      var $commentID = $root.attr("comment_id");
      var $threadCount = $root.attr("thread_count");
      var $newThreadCount = parseInt($threadCount) + 1;
      var $storyID = $("#currentStory").attr("data-story-id");
      var $text = $root.find(".response").val();
      var $guestName = $root.find(".responseGuestName").val();
      var $userID = $("#sidebar").attr("data-user-id");
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
   
   
   */
   
   
   
   
   
   /* send user message button */
   
   /*
   Note: for now I'm getting rid of all this, because we can't have popups inside of popups
   it would get a little too convoluted.
   Or maybe it's not that bad.
   
   
   //$(".messageUser").click(function(){
   $("#newsBox").on("click", ".messageUser", function(){
      $("#messageUserMask").show();
      var passiveUser = $(this).parents(".comment").attr("user_id");
      $("#messageUserBox").attr("passive_user", passiveUser);
      $("#submitDirect").show();
      $("#messsageAlert").hide();
            
      var thisComment = $(this).parent().parent().find(".mainText").text();
      $("#message").text("In response to '"+thisComment+"'... ");
   });
   
   //$("#messageClose").click(function(){
   $("#newsBox").on("click", ".messageUser", function(){
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
   */
   
});