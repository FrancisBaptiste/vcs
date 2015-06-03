$(function(){

	$("#userLookup").focus(function(){
		$(this).val("");
	});

	$("#userLookup").keyup(function(){
    	var thisText = $(this).val().toLowerCase();
    	if(thisText != ""){
        	$("#allUsers .name").each(function(){
            	var thisName = $(this).text().toLowerCase();
            	if(thisName.indexOf(thisText) !== -1){
	            	$(this).parent().fadeIn();
	            	var userImage = $(this).parent().attr("image_path");
	            	if(userImage == ""){
		            	userImage = "http://vancitysocial.ca/images/noProfile.jpg";
	            	}
	            	$(this).parent().find(".imageSpace").html("<img src='"+userImage+"' width='20' height='20' />");
            	}else{
	            	$(this).parent().fadeOut();
            	}
        	});
    	}
	});

	//seding the user a message
	$(".sendUserMessage").click(function(){
      $("#messageUserMask").show();
      var passiveUser = $(this).parent().attr("data-friend-id");
      $("#messageUserBox").attr("data-passive-user", passiveUser);
      $("#submitDirect").show();
      $("#messsageAlert").hide();
   });

   /*
   $("#messageClose").click(function(){
      $("#messageAlert").hide();
   	  $("#submitDirect").show();
      $("#message").val("");
      $("#messageUserMask").hide();
   });

   $("#messageButton").click(function(){
      $thisMess = $("#message").val();
      var pUser = $(this).parents("#messageUserBox").attr("passive_user");
      var aUser = $("#sidebar").attr("data-user-id");
      $.post("functions/directMessage.php", {passive: pUser, active: aUser, message:$thisMess}, function(data){
	      if(data == "true"){
		      $("#messageAlert").show();
		      $("#submitDirect").hide();
	      }
      });
   });
   */


   $(".addToFriendlist").click(function(){
	   var pUser = $(this).parent().attr("data-friend-id");
	   var aUser = $("#sidebar").attr("data-user-id");
	   var toHide = $(this).parent();
	   var friendImage = $(this).parent().attr("image_path");
	   if(friendImage == ""){ friendImage = "images/noProfile.jpg"; }
	   var friendName = $(this).parent().find(".name").text();
       $.post("functions/addFriend.php", {friend: pUser, user: aUser}, function(data){
	      if(data == "true"){
		      toHide.hide();
		      var friendContent = "<li><span class='friendImage' style='margin-right: 10px; position:relative; top: 5px;'>";
		      friendContent += "<img src='"+friendImage+"' width='20' height='20'/>";
		      friendContent += "</span><span class='name'>" + friendName + "</span><span class='removeFriend'><img src='images/x.png'</span></li>";
		      $("#userFriendlist").append(friendContent);
	      }else if(data=="false"){
		      alert("Something went wrong. Friend was not added.");
	      }else{
		      alert(data);
	      }
       });
   });

   $("#userFriendlist").on("mouseenter", "li", function(){
   		$(this).find(".removeFriend").show();
	});
	$("#userFriendlist").on("mouseleave", "li", function(){
   		$(this).find(".removeFriend").hide();
	});

   $("#userFriendlist").on("click", ".removeFriend", function(){
	   var pUser = $(this).parent().attr("data-friend-id");
	   var aUser = $("#sidebar").attr("data-user-id");
	   var toHide = $(this).parent();
	   $.post("functions/removeFriend.php", {friend: pUser, user: aUser}, function(data){
	      if(data == "true"){
		      toHide.fadeOut(function(){
			      toHide.remove();
		      });
	      }else if(data=="false"){
		      alert("Something went wrong. Friend was not added.");
	      }else{
		      alert(data);
	      }
       });
   });

   $(".clearLookup").click(function(){
	   $("#userLookup").val("");
	   $(".userLine").fadeOut();
   });

});
