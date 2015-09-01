$food = ['food', 'dinner', 'lunch', 'breakfast', 'brunch', 'restaurant', 'restaurants', 'diner', 'cafe', 'bar', 'pub', 'drink', 'drinks', 'pint', 'brew', 'ate', 'cook', 'cooks', 'cookie', 'cookies', 'dessert', 'desserts', 'cooking', 'recipe', 'recipies', 'ingredients', 'sugar', 'salt', 'munch', 'munchie', 'munchies', 'chip', 'chips', 'tasty', 'delicious', 'food', 'chef', 'kitchen', 'chocolate', 'fruit', 'bread', 'baking', 'banana', 'pie', 'pies', 'cake', 'cakes', 'sweets', 'hungry', 'starving', 'eat', 'bite', 'meal', 'eat'];
$sports = ['hockey', 'football', 'basketball', 'golf', 'tennis', 'sports', 'playoffs', 'nhl', 'mlb', 'nba', 'playoff', 'penalty', 'shootout', 'ot', 'canucks', 'blackhawks', 'ranger', 'habs', 'flames', 'fifa'];
$music = ['band', 'guitar', 'drums', 'concert', 'performance', 'singer', 'singing', 'sing', 'venue', 'music', 'tune', 'tunes', 'beat', 'dj', 'mixtape', 'listening', 'song', 'album'];
$tv = ['tv', 'movie', 'movies', 'hbo', 'nbc', 'sitcom', 'theatre', 'theater', 'watching', 'watched'];
$health = ['running', 'run', 'biking', 'bike', 'seawall', 'hike', 'hiking', 'trail', 'yoga', 'fitness', 'health', 'healthy', 'diet', 'dieting', 'exercise','exercising','exercizing', 'crossfit', 'gym', 'workout', 'fitbit', 'situps', 'climbing', 'swim', 'swimming', 'climb', 'pool', 'golfing', 'snowboarding', 'skating'];
$books = ['read', 'reading', 'book', 'books', 'writer', 'author', 'library', 'magazine', 'textbook'];

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
    	$userID = $("#sidebar").attr("data-user-id");
        if(e.which == 13){
            if($userID != ""){
            	if($("#post").val() != ""){
                	createPost();
				}
		    }else{
			    alert("Sorry. Only signed in users can post to the timeline.");
		    }
        }else if(e.which == 32 && $("#posts").attr("data-interest") == 0){
	        //check to see if any words are in the interests_keywords tables
	        $thisPost = $("#post").val();
	        $words = $thisPost.split(' ');
	        $lastWord = $words[$words.length-1].replace(".", "").toLowerCase();
	        //check this word against he db.
	        //just loading a giant array onto the page might be easier.
	        if($food.indexOf($lastWord) !== -1){
		        $("#suggestion").html("Suggested Topic: <span data-interest='3'>food & drink</span> ").fadeIn();
	        }else if($sports.indexOf($lastWord) !== -1){
		        $("#suggestion").html("Suggested Topic: <span data-interest='4'>sports</span> ").fadeIn();
	        }else if($music.indexOf($lastWord) !== -1){
		        $("#suggestion").html("Suggested Topic: <span data-interest='5'>music</span> ").fadeIn();
	        }else if($tv.indexOf($lastWord) !== -1){
		        $("#suggestion").html("Suggested Topic: <span data-interest='6'>movies & tv</span> ").fadeIn();
	        }else if($health.indexOf($lastWord) !== -1){
		        $("#suggestion").html("Suggested Topic: <span data-interest='7'>health & fitness</span> ").fadeIn();
	        }else if($books.indexOf($lastWord) !== -1){
		        $("#suggestion").html("Suggested Topic: <span data-interest='8'>books</span> ").fadeIn();
	        }

        }
    });

    $("#main").on("click", "#suggestion span", function(){
	    if($("#suggestion").attr("data-selected") == 1){
		    $("#suggestion").fadeOut();
		    $("#suggestion").attr("data-selected", 0);
	    }else{
		    $thisInterestID = $(this).attr('data-interest');
			$thisInterestName = $(this).text();
			$("#posts").attr('data-interest', $thisInterestID);
			$("#postButton").text("Post to " + $thisInterestName);
			$("#suggestion").html("revert to <span data-interest='0'>all posts</span>");
			$("#suggestion").attr("data-selected", 1);
	    }
	});

    $("#post").focus(function(){
        if ($(this).val() == "What's going on?") {
			$(this).val("");
		}
    });

    function createPost(){
        $val = $("#post").val();
        $user = $("#sidebar").attr("data-user-id");
        //$interest = "<?php echo $i; ?>";
        $interest = $("#posts").attr("data-interest");
        $name = $("#sidebar").attr("data-user-name");
        $postImage = $("#sidebar").attr("data-user-pic");
        $.post("functions/createPost.php", {user: $user, post: $val, interest: $interest}, function(data){
            if(data == "true"){
                $("#post").val("");
                $newContent = "<div class='post'>";
                //$newContent += '<div class="topicTag" style="background-color: rgb(112, 112, 158)"><span><a href="app.php?i=0">all posts</a></span></div>';
                $newContent += getInterest($interest);
                $newContent += "<div class='picMask'>"+$postImage+"</div>";
                $newContent += "<div><p><strong>"+$name+"</strong> <span class='timePosted'>just now</span><br/><span class='mainText'>"+$val+"</span></p></div>";
                $newContent += "<div class='addComment'>Add a Comment</div><div class='breaker'></div>";
                $("#posts").prepend($newContent);
                if ($("#suggestion").attr("data-selected") == 1) {
	            	$("#posts").attr('data-interest', 0);
					$("#postButton").text("Post to All");
	            }
	            $("#suggestion").fadeOut();
            }else{
                alert("technical difficulties, try posting again later");
            }
        });
    }
    function getInterest($id){
	    if($id == 0){
		    return '<div class="topicTag" style="background-color: rgb(112, 112, 158)"><span><a href="app.php?i=0">all posts</a></span></div>';
	    }else if($id == 1){
		    return '<div class="topicTag" style="background-color: rgb(68,68,220)"><span><a href="app.php?i=1">news</a></span></div>';
	    }else if($id == 2){
		    return '<div class="topicTag" style="background-color: rgb(130,130,225)"><span><a href="app.php?i=2">events</a></span></div>';
	    }else if($id == 3){
		    return '<div class="topicTag" style="background-color: #9150B1"><span><a href="app.php?i=3">food & drink</a></span></div>';
	    }else if($id == 4){
		    return '<div class="topicTag" style="background-color: rgb(107, 174, 108)"><span><a href="app.php?i=4">sports</a></span></div>';
	    }else if($id == 5){
		    return '<div class="topicTag" style="background-color: rgb(195, 103, 168)"><span><a href="app.php?i=5">music</a></span></div>';
	    }else if($id == 6){
		    return '<div class="topicTag" style="background-color: rgb(120,160,210)"><span><a href="app.php?i=6">movies & tv</a></span></div>';
	    }else if($id == 7){
		    return '<div class="topicTag" style="background-color: rgb(40,130,175)"><span><a href="app.php?i=7">health & fitness</a></span></div>';
	    }else if($id == 8){
		    return '<div class="topicTag" style="background-color: rgb(71,185,195)"><span><a href="app.php?i=8">books</a></span></div>';
	    }else if($id == 9){
		    return '<div class="topicTag" style="background-color: rgb(10,155,170)"><span><a href="app.php?i=9">arts</a></span></div>';
	    }else if($id == 10){
		    return '<div class="topicTag" style="background-color: rgb(83, 205, 115)"><span><a href="app.php?i=10">shopping & fashion</a></span></div>';
	    }else if($id == 11){
		    return '<div class="topicTag" style="background-color: rgb(20,175,100)"><span><a href="app.php?i=11">books</a></span></div>';
	    }else if($id == 12){
		    return '<div class="topicTag" style="background-color: rgb(182,90,90)"><span><a href="app.php?i=12">open question</a></span></div>';
	    }
    }

    //functionality for the 'load more' button
    $("#loadMore").click(function(){
        $postIndex = $(".post:last-child").attr("data-post-id");
        $interest = $("#posts").attr("data-interest");
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



	$("#main").on("click", ".userRollover", function(){
	    $(this).parent().parent().find(".userInformation").stop(true,true).fadeIn();
	    $("#fullMask").fadeIn();
    });
    $("#main").on("click", ".picMask", function(){
	    $(this).parent().find(".userInformation").first().stop(true,true).fadeIn();
	    $("#fullMask").fadeIn();
    });

	$("#main").on("click", ".userInformation", function(){
	    $(this).parent().parent().find(".userInformation").stop(true,true).fadeOut();
	    $("#fullMask").fadeOut();
    });


    $("#fullMask").click(function(){
	    $(".userInformation").fadeOut();
	    $("#fullMask").fadeOut();
    });


	$("#main").on("click", ".userButtonFriend", function(){
	   var pUser = $(this).parents(".userInformation").attr("data-user-id");
	   var aUser = $("#sidebar").attr("data-user-id");
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
		$("#messageNote").html("*Note: Your message will be sent directly to this user's inbox.");
		$("#messageHeaderText").text("Message User Directly");
		$("#messageUserMask").show();
		var passiveUser = $(this).parents(".userInformation").attr("data-user-id");
		$("#messageUserBox").attr("data-passive-user", passiveUser);
		$("#submitDirect").show();
		$("#messsageAlert").hide();

		var thisComment = $(this).parents(".post").find(".mainText:first").text();
		//$("#message").val("In response to '"+thisComment+"'... ");
   });

   $("#messageClose").click(function(){
      $("#messageAlert").hide();
   	  $("#submitDirect").show();
      $("#message").val("");
      $("#messageUserMask").hide();
   });

   $("#messageButton").click(function(){
      $thisMess = $("#message").val();
      var pUser = $(this).parents("#messageUserBox").attr("data-passive-user");
      var aUser = $("#sidebar").attr("data-user-id");
      $.post("functions/directMessage.php", {passive: pUser, active: aUser, message:$thisMess}, function(data){
	      if(data == "true"){
		      $("#messageAlert").show();
		      $("#submitDirect").hide();
	      }else{
		      alert("Sorry. Something is broken.");
	      }
      });
   });

   $("#message").keypress(function(e){
         if(e.which == 13){
             if($("#message").val() != ""){
                 $thisMess = $("#message").val();
			      var pUser = $(this).parents("#messageUserBox").attr("data-passive-user");
			      var aUser = $("#sidebar").attr("data-user-id");
			      $.post("functions/directMessage.php", {passive: pUser, active: aUser, message:$thisMess}, function(data){
				      if(data == "true"){
					      $("#messageAlert").show();
					      $("#submitDirect").hide();
				      }else{
					      alert("Sorry. Something is broken.");
				      }
			      });
             }
         }
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
		$("#messageUserBox").attr("data-passive-user", passiveUser);
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

	if($("#footerForgot")){
		$("#footerForgot").click(function(){
			$("#fullScreen").fadeIn(function(){
				$("#forgotBox").fadeIn();
			});
		});
	}

	$("#aboutClose").click(function(){
		$("#aboutBox").fadeOut(function(){
			$("#fullScreen").fadeOut();
		});
	});

	$("#forgotClose").click(function(){
		$("#forgotBox").fadeOut(function(){
			$("#fullScreen").fadeOut();
		});
	});

	$("#editClose").click(function(){
		$("#editBox").fadeOut(function(){
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
		}else if($("#editBox").css("display") != "none"){
			$("#editBox").fadeOut(function(){
				$("#fullScreen").fadeOut();
			});
		}else if($("#welcome").css("display") != "none"){
			$("#welcome").slideUp(function(){
				$("#fullScreen").fadeOut();
			});
		}
	});

	$("#loginButton").click(function(){
		$("#signupBlock").hide();
		$("#loginBlock").show();
		$("#fullScreen").fadeIn(function(){
			$("#invitationBox").fadeIn();
		});
	});

	$("#inviteBtn").click(function(){
		$("#loginBlock").slideUp(function(){
			$("#signupBlock").slideDown();
		});
	});

	$(".newsImage, .newsTitle").click(function(){
		var newsID = $(this).attr("data-news-id");
		//var thisY = $(this).offset().top;
		var thisY = $(document).scrollTop();
		$("#fullScreen").fadeIn();
		$.get("news_popup.php", {story: newsID}, function(data){
			$("#newsBox").html(data);
			$("#newsBox").css("top", thisY);
			$("#newsBox").fadeIn();
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

	$(".top-menu span").click(function(){
		$("#fullScreen").fadeIn(function(){
			$("#editBox").fadeIn();
		});
	});


	$("#cancelEdit").click(function(){
        $("#editBox").fadeOut(function(){
			$("#fullScreen").fadeOut();
		});
    });

	// this function changes the links on the notifications for the INBOX
	if( $(window).width() < 800 ){
		$(".messNot a").each(function(){
			var link = $(this).attr("href");
			var newLink = link.replace("inbox.php", "mobile-inbox.php");
			$(this).attr("href", newLink);
		});
	}

});