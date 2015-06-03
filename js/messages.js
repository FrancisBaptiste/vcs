$(function(){

   $("#messageButtonInbox").click(function(){
         createMessage();
     });
     $("#messageInbox").keypress(function(e){
         if(e.which == 13){
             if($("#messageInbox").val() != ""){
                 createMessage();
             }
         }
     });


     $("#allConversations li").click(function(){
         var conID = $(this).attr("data-conversation-id");
         var messURL = 'api/messages.php?id=' + conID;

         $.getJSON(messURL, function(data){
	         var newContent = "";
	         $.each(data, function(k, v){
                        newContent += '<div class="message post">';
                        newContent += '<div class="picMask"><img src="' + v.user.image + '"></div>';
                        newContent += '<p><strong>'+ v.user.name +'</strong> '+ v.text +' <em>' + v.date + '</em></p></div>';
	         });
	         $("#allMessages").html(newContent);
             $("#allMessages").attr("data-conversation-id", conID);
         });

         $u = $("#sidebar").attr("data-user-id");
         var thisUnread = $(this).find(".unread");
         $.post("functions/updateInbox.php", {conversation: conID, user:$u}, function(data){
            thisUnread.remove();
         });
     });


    function createMessage(){
         var $val = $("#messageInbox").val();
         var $user = $("#sidebar").attr("data-user-id");
         var $conID = $("#allMessages").attr("data-conversation-id");
         var $name = $("#sidebar").attr("data-user-name");
         var $postImage = $("#sidebar").attr("data-user-pic");

         $.post("functions/createMessage.php", {user: $user, message: $val, conversation: $conID}, function(data){
             if(data == "true"){
                 $("#messageInbox").val("");
                 $newContent = "<div class='message post'><div class='picMask'>"+$postImage+"</div><p><strong>"+$name+"</strong> " + $val + " <em>just now</em></div>";
                 $("#allMessages").append($newContent);
             }else{
                 alert("technical difficulties, try posting again later");
             }
         });
     }

});
