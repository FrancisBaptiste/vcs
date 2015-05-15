$(function(){
   
   $("#messageButton").click(function(){
         createMessage();
     });
     $("#message").keypress(function(e){
         if(e.which == 13){
             if($("#message").val() != ""){
                 createMessage();
             }
         }
     });
     
     
     $("#allConversations li").click(function(){
         var conID = $(this).attr("conversation_id");
         var messURL = 'api/messages.php?id=' + conID;
         
         $("#allMessasges").attr("conversation_id", conID);
         
         $.getJSON(messURL, function(data){
	         var newContent = "";
	         $.each(data, function(k, v){                        
                        newContent += '<div class="message post">';
                        newContent += '<div class="picMask"><img src="' + v.user.image + '"></div>';
                        newContent += '<p><strong>'+ v.user.name +'</strong> '+ v.text +' <em>' + v.date + '</em></p></div>';
	         });
	         $("#allMessages").html(newContent);
                 $("#allMessages").attr("conversation_id", conID);
         });
         
         $u = $("#sidebar").attr("user_id");
         var thisUnread = $(this).find(".unread");
         $.post("functions/updateInbox.php", {conversation: conID, user:$u}, function(data){
            thisUnread.remove();
         });
     });
     
    
    function createMessage(){
         var $val = $("#message").val();
         var $user = $("#sidebar").attr("user_id");
         var $conID = $("#allMessages").attr("conversation_id");
         var $name = $("#sidebar").attr("user_name");
         var $postImage = $("#sidebar").attr("user_pic");
         
         $.post("functions/createMessage.php", {user: $user, message: $val, conversation: $conID}, function(data){
             if(data == "true"){
                 $("#message").val("");
                 $newContent = "<div class='message post'><div class='picMask'>"+$postImage+"</div><p><strong>"+$name+"</strong> " + $val + " <em>just now</em></div>";
                 $("#allMessages").append($newContent);
             }else{
                 alert("technical difficulties, try posting again later");
             }
         });
     }
    
});
