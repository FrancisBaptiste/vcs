$(function(){
    $("#postButton").click(function(){
    	if($userID != ""){
        	if($("#post").val() != ""){
            	createPost();
			}
	    }else{
		    alert("Sorry. Only signed in users can post to the timeline.");
	    }
    });
    $("#post").keypress(function(e){
    	$userID = $("#sidebar").attr("user_id");
        if(e.which == 13){
            if($userID != ""){
            	if($("#post").val() != ""){
                	createPost();
				}
		    }else{
			    alert("Sorry. Only signed in users can post to the timeline.");
		    }
        }
    });
    
    $("#post").focus(function(){
        if ($(this).val() == "What's going on?") {
			$(this).val("");
		}
    });
    
    function createPost(){
        $val = $("#post").val();
        $user = $("#sidebar").attr("user_id");
        //$interest = "<?php echo $i; ?>";
        $interest = $("#posts").attr("interest");
        $name = $("#sidebar").attr("user_name");
        $postImage = $("#sidebar").attr("user_pic");
        $.post("functions/createPost.php", {user: $user, post: $val, interest: $interest}, function(data){
            if(data == "true"){
                $("#post").val("");
                $newContent = "<div class='post'><div class='picMask'>"+$postImage+"</div><p><strong>"+$name+"</strong> " + $val + " <em>just now</em></div>";
                $("#posts").prepend($newContent);
            }else{
                alert("technical difficulties, try posting again later");
            }
        });
    }
    
    //functionality for the 'load more' button
    $("#loadMore").click(function(){
        $postIndex = $(".post:last-child").attr("post_id");
        $interest = $("#posts").attr("interest");
        $.post("functions/loadMore.php", {postIndex: $postIndex, i: $interest}, function(data){
            $("#posts").append(data);
        });
    });
    
    
    $("#signupBtn").click(function(){
        $("#loginBlock").hide();
        $("#signupBlock").show();
    });
	
    
    $("#loginBtn").click(function(){
        $("#loginBlock").show();
        $("#signupBlock").hide();
    });
    
    
    
    $("#editProfile").click(function(){
        $("#editUser").show();
        $("#userBlock").hide();
    });
    
    $("#cancelEdit").click(function(){
        $("#editUser").hide();
        $("#userBlock").show();
    });
    

    
    //$(".userRollover").bind("mouseenter", function(){
	$("#main").on("click", ".userRollover", function(){
    	$(".userInformation").hide();
	    $(this).parent().parent().find(".userInformation").stop(true,true).fadeIn();
    });
    
    //$(".userInformation").bind("mouseleave", function(){
	$("#main").on("click", ".userInformation", function(){
	    $(this).parent().parent().find(".userInformation").stop(true,true).fadeOut();
    });
    
    
    //$(".userButtonFriend").click(function(){
	$("#main").on("click", ".userButtonFriend", function(){
	   var pUser = $(this).parents(".post").attr("user_id");
	   var aUser = $("#sidebar").attr("user_id");
	   var toChange = $(this);
       $.post("functions/addFriend.php", {friend: pUser, user: aUser}, function(data){
	      if(data == "true"){
		      toChange.text("Added as Friend");
	      }else if(data=="false"){
		      alert("Something went wrong. Friend was not added.");
	      }else{
		      alert(data);
	      }
       });
   });
    
    // this is cut and paste from the js on the news page
     /* send user message button */
   // this used to point to .userRollover
   //$(".userButtonMessage").click(function(){
	$("#main").on("click", ".userButtonMessage", function(){
		$("#messageNote").html("*Note: Your message will be sent directly to this user's inbox.<br/>The auto-filled text is to give the receiver context.");
		$("#messageHeaderText").text("Message User Directly");
      $("#messageUserMask").show();
      var passiveUser = $(this).parents(".post").attr("user_id");
      $("#messageUserBox").attr("passive_user", passiveUser);
      $("#submitDirect").show();
      $("#messsageAlert").hide();
            
      var thisComment = $(this).parents(".post").find(".mainText:first").text();
      $("#message").val("In response to '"+thisComment+"'... ");
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
	      }else{
		      alert("Sorry. Something is broken.");
	      }
      });
   });
   
   
   $("#topicsTop").click(function(){
	   if($("#topicsDropDown").css("display") == "none"){
		   $("#topicsDropDown").slideDown();
		   $("#dropDownArrow").css("background-position", "0, 0");
	   }else{
		   $("#topicsDropDown").slideUp();
		   $("#dropDownArrow").css("background-position", "10px, 0");
	   }
   });
    
	$("#footerContact").click(function(){
		$("#messageUserMask").show();
		var passiveUser = 33;
		$("#messageUserBox").attr("passive_user", passiveUser);
		$("#submitDirect").show();
		$("#messsageAlert").hide();
		$("#message").val("");
		
		$("#messageNote").html("If you have any questions, would like to report a bug, or just want to say hi, you can use this box to send us a direct message.");
		$("#messageHeaderText").text("Contact Vancity Social");
	});
    
	
	$("#footerAbout").click(function(){
		$("#fullScreen").fadeIn(function(){
			$("#aboutBox").fadeIn();
		});
	});
	
	$("#aboutClose").click(function(){
		$("#aboutBox").fadeOut(function(){
			$("#fullScreen").fadeOut();
		});
	});
	
	$("#fullScreen").click(function(){
		if($("#newsBox").css("display") != "none"){
			$("#newsBox").fadeOut(function(){
				$("#fullScreen").fadeOut();
			});
		}else if ($("#invitationBox").css("display") != "none") {
			$("#invitationBox").fadeOut(function(){
				$("#fullScreen").fadeOut();
			});
		}
	});
	
	$("#inviteBtn").click(function(){
		$("#fullScreen").fadeIn(function(){
			$("#invitationBox").fadeIn();
		});
	});
	
	$(".newsImage, .newsTitle").click(function(){
		var newsID = $(this).attr("data-news-id");
		//var thisY = $(this).offset().top;
		var thisY = $(document).scrollTop();
		$.get("news_popup.php", {story: newsID}, function(data){
			$("#newsBox").html(data);
			$("#newsBox").css("top", thisY);
			$("#fullScreen").fadeIn(function(){
				$("#newsBox").fadeIn();
			});
		});
	});
	
	
	
	//for the invitation request
	$("#requestInviteBtn").click(function(){
		if ($("#nameRequest").val() == "" || $("#emailRequest").val() == "" || $("#passwordRequest").val() == "" ){
			alert("Enter all required fields");
		}else{
			var thisName = $("#nameRequest").val();
			var thisEmail = $("#emailRequest").val();
			var thisPassword = $("#passwordRequest").val();
			if ($("#twitterRequest").val() != "") {
				var thisTwitter = $("#twitterRequest").val();
			}else{
				var thisTwitter = "none";
			}
			$.post("functions/sendInvitationRequest.php", {name:thisName, email:thisEmail, password:thisPassword, twitter:thisTwitter}, function(data){
				if (data == "true") {
					$("#signupBlock").fadeOut(function(){
						$("#invitationBox").html("Thank You for sending your request. You will recieve a confirmation in the next 48 hours.");
					})
				}else{
					alert("Request could not be made");
				}
			});
		}
	});
});