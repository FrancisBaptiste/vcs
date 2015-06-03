<?php

require("includes/setup.php");

if(isset($_COOKIE['user'])){
    $id = $_COOKIE['user'];
}else{
    $id = null;
}

require("includes/encryption.php");
$decodedID = decrypt_id($id);

$json = file_get_contents(SITE_URL . "api/user.php?id=$decodedID");
$decoded = json_decode($json);

$name = $decoded->name;
$friends = explode(",", $decoded->friendlist);
if($decoded->image == ""){
    $image = "<img src='".SITE_URL."images/noProfile.jpg' id='profilePic'/>";
    $postImage = "<img src='".SITE_URL."images/noProfile.jpg'/>";
}else{
    $image = "<img src='$decoded->image' id='profilePic' />";
    $postImage = "<img src='$decoded->image' />";
}
$about = $decoded->about;

include("includes/checkInbox.php");

if(isset($_GET['i'])){
    $i = $_GET['i'];
    $content = file_get_contents(SITE_URL."api/posts.php?interest=$i");
    $currentI = $db->query("SELECT interest FROM interests WHERE id=$i");
    while($ci = $currentI->fetch_assoc() ){
	    $currentInterest = $ci['interest'];
    }
}else if(isset($_GET['list'])){
	$content = file_get_contents(SITE_URL."api/posts.php?friendlist=$decodedID");
    $currentInterest = "FriendList";
}else{
    $i = 0;
    $content = file_get_contents(SITE_URL."api/posts.php");
    $currentInterest = "All";
}
$contentDecoded = json_decode($content);

$notificationsAPI = file_get_contents(SITE_URL."api/notifications.php?id=$decodedID");
$notifications = json_decode($notificationsAPI);


include("includes/functions.php");

?>

<!DOCTYPE html>
    <head>
    	<title>Vancity Social</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/notifications.js"></script>
        <script type="text/javascript" src="js/comments.js"></script>
        <script type="text/javascript" src="js/favorites.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/news_popup.js"></script>
        <?php
	    if(isset($_GET['welcome'])){ echo "<link href='http://fonts.googleapis.com/css?family=Roboto:700,300,400' rel='stylesheet' type='text/css'>"; }
	    ?>
    </head>

    <body>
    	<?php include_once("analyticstracking.php") ?>

    	<div id="fullMask"></div>

        <div id="wrapper">

            <div id="mainHeader">
                <img src="images/logo2.png"/>
                <?php if($id == null){
	                echo "<div id='loginButton'>Member log in</div>";
	                echo "<div id='buttonDivide'> | </div>";
	                echo "<div id='redeemButton'><a href='redeem.php'>Redeem invite code</a></div>";
	            }else{
		            include("includes/top-menu.php");
		        }
	            ?>
            </div>


            <div id="sidebar" data-user-id="<?php echo $id; ?>" data-user-name="<?php echo $name;?>" data-user-pic="<?php echo $postImage;?>">
                <div>
	                <h3 class="sectionHeader"><?php if($id!=null){ echo "All"; }?>Topics</h3>
	                <ul id="allTopics">
	                    <?php
	                    $interestsQ = $db->query("SELECT * FROM interests LIMIT 13");
	                    while($iRow = $interestsQ->fetch_assoc() ){
	                        $thisInterest = $iRow['interest'];
	                        $thisInterestId = $iRow['id'];
	                        if(!in_array($thisInterestId, $userInterests)){
	                            echo "<li style='border-color: ". colorTag($thisInterestId) .";'><a href='".SITE_URL."app.php?i=$thisInterestId'>$thisInterest </a>";
	                            if(isset($_COOKIE['user'])){
	                                echo "<span class='addFav' data-topic-id='$thisInterestId'>[+]</span></li>";
	                            }else{
	                                echo "<span class='addFav' data-topic-id='$thisInterestId' style='display:none;'>[+]</span></li>";
	                            }
	                        }
	                    }
						#echo "<li><a href='".SITE_URL."topics.php'>View All Topics...</a>";
	                    ?>
	                </ul>
                </div>
            </div>


            <!-- ------------------------------------ start of the Main Content --------------------------------- -->
            <div id="main">
            	<?php echo $messageNotifications; # declared in includes/checkInbox.php ?>

				<?php
				$unreadCount = 0;
				foreach($notifications as $n){
                    if($n->notification_read == 0){
                    	$unreadCount++;
                    }
                }

				if($unreadCount > 0){
					echo "<div id='notificationsMessage'>";
					echo "<h3 class='sectionHeader'>You Have New Notifications!</h3>";

                    foreach($notifications as $n){
                        if($n->notification_read == 0){
                            echo "<div class='notificationAlert notification' data-fetch-pid='$n->post_id' data-nid='$n->id'><span>$n->commenter_name</span> commented on something you posted.</div>";
                        }
                    }
					echo "</div>";
				}
				?>

        		<h3 id="topicsTop" class='sectionHeader'>Current Topic: <?php echo ucwords($currentInterest); ?><span id="dropDownArrow"></span></h3>
				<div id="topicsDropDown" style="display:none;">
					<ul id="dropDownTopics">
						<?php
	                    $interestsQ = $db->query("SELECT * FROM interests LIMIT 13");
	                    while($iRow = $interestsQ->fetch_assoc() ){
	                        $thisInterest = $iRow['interest'];
	                        $thisInterestId = $iRow['id'];
	                        echo "<li><a href='".SITE_URL."app.php?i=$thisInterestId'>$thisInterest</a></li>";
	                    }
	                    ?>
					</ul>
				</div>

                <div id="submit">
                    <textarea name="post" id="post" cols="25" rows="5">What's going on?</textarea>
                    <div id="postButton">Post to <?php echo ucwords($currentInterest); ?></div>
                    <div id="suggestion" data-selected="0">Suggested Topic: <span></span></div>
                </div>

                <div id="posts" data-interest="<?php echo $i; ?>" data-interest-name="<?php echo ucwords($currentInterest); ?>">

                <?php
                foreach($contentDecoded as $post){
                ?>
                    <div class="post" data-post-id="<?php echo $post->id; ?>" data-user-id="<?php echo $post->user_id ?>">

	                    <?php
                    	$postInterest = $post->interest;
                        $getInterest = $db->query("SELECT * FROM interests WHERE id=$postInterest");
                        while($pi = $getInterest->fetch_assoc()){
	                        $thisPostInterest = $pi['interest'];
                        }
                    	?>
                    	<div class="topicTag" style="background-color: <?php echo colorTag($postInterest);?>"><span><a href="app.php?i=<?php echo $postInterest; ?>"><?php echo $thisPostInterest; ?></a></span></div>

                        <?php
                            echo "<div class='picMask'><img src='". $post->user_image ."'/></div>";
                        ?>



                        <div>

	                        <div class='userInformation' data-user-id="<?php echo $post->user_id ?>">
	                    		<img src='<?php echo $post->user_image; ?>'/>
								<div>
									<span><?php echo $post->name; ?></span>
									<p><span>About: </span><?php echo $post->user_about; ?></p>
									<?php
									if(in_array($post->user_id, $friends)){
										echo "<div class='userButton' style='cursor:auto;'>Friends</div>";
									}else{
										echo "<div class='userButton userButtonFriend'>Add As Friend</div>";
									}
									?>

									<div class='userButton userButtonMessage'>Send Message</div>
									<div class='actionMessage'></div>
								</div>
	                    	</div>

                        	<?php
                        	#look for urls and turn them into links before displaying post
							$thisPost = makeClickableLinks($post->text);

							if($post->interest == 1 && $post->news_id != 0){
						    	$newsID = $post->news_id;
						    	$getTitle = $db->query("SELECT title FROM news WHERE id=$newsID");
						    	while($newsTitle = $getTitle->fetch_assoc() ){
							    	$thisTitle = $newsTitle['title'];
						    	}

							    $thisPost = "<span class='newsPost'>commented on the story <a class='newsTitle' data-news-id='$newsID'>$thisTitle</a>...</span> $thisPost";


						    }else if($post->interest == 42){
								$thisPost = "<span class='newsPost'>updated my 'about' blurb to...</span> $thisPost";
							}

                        	?>

	                        <p>
		                        <strong class='userRollover' data-user-id='<?php echo $post->user_id; ?>'><?php echo $post->name; ?></strong>
		                        <?php
		                        	$postTime = strtotime($post->date);
									include("includes/timeFormat.php");
		                        	echo "<span class='timePosted'>$postTime</span>";
		                        ?>
		                        <br/>
		                        <span class='mainText'><?php echo $thisPost; ?></span>
		                    </p>
                        </div>

                        <?php

                        if(count($post->comments)>0){
                        	$commentCount = 0;
                        	$commentLimit = 4;
                            foreach($post->comments as $comment){
                            	$commentCount++;
                            	if($commentCount <= $commentLimit){
	                            	echo "<div class='comment'>";
                            	}else{
	                            	echo "<div class='comment hiddenComment'>";
                            	}
                                echo "<div class='picMask'><img src='".$comment->image."'/></div>";
                                $thisComment = makeClickableLinks($comment->text);

                                $postTime = strtotime($comment->date);

                                include("includes/timeFormat.php");

                                echo "<p><strong class='userRollover'>".$comment->user_name."</strong> <span class='timePosted'>$postTime</span><br/> <span class='mainText'>".$thisComment."</span></p>";


                                #user pop ups for the comments
                                echo "<div class='userInformation' data-user-id='$comment->user_id'>";
	                        	echo "<img src='$comment->image'/>";
								echo "	<div>";
								echo "		<span>$comment->user_name</span>";
								echo "		<p><span>About: </span>$comment->user_about</p>";
								if(in_array($comment->user_id, $friends)){
									echo "<div class='userButton' style='cursor:auto;'>Friends</div>";
								}else{
									echo "<div class='userButton userButtonFriend'>Add As Friend</div>";
								}
								echo "		<div class='userButton userButtonMessage'>Send Message</div>";
								echo "		<div class='actionMessage'></div>";
								echo "	</div>";
	                        	echo "</div>";
	                        	#end of user popups for the comments

	                        	echo "</div>"; #end of comment div

                            }
                            if($commentCount > $commentLimit){
	                            echo "<div class='viewAllComments'>View All Comments ($commentCount)</div>";
                            }
                        }

                        echo "<div class='addComment'>Add a Comment</div>";

                        ?>

                        <div class="breaker"></div>
                    </div>

                <?php
                }
                ?>
                </div>

                <div id="loadMore">Load More</div>

            </div>


            <!-- -------------------------------------------------------------
                Start of news area
                -------------------------------------------------------- -->

            <div id="right">
                <h3 class='sectionHeader'>News <?php # if($i != 0 && $i != 1){ echo ucwords($currentInterest);} ?></h3>
                <?php
	            if($i != 0 && $i != 1){
		            $newsQ = $db->query("SELECT * FROM news WHERE topic=$i ORDER BY id DESC LIMIT 10");
	            }else{
		            $newsQ = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT 10");
	            }
                while($news = $newsQ->fetch_assoc() ){
                    echo "<div class='newsCard'><img src='$news[image]' style='width:100%;' class='newsImage' data-news-id='".$news[id]."'/>";
                    #echo "<h4><a href='news.php?story=$news[id]'>" . stripslashes($news[title]) . "</a></h4>";
                    echo "<h4 class='newsTitle' data-news-id='".$news[id]."'>" . stripslashes($news[title]) . "</h4></div>";
                }
                ?>
            </div>
        </div> <!-- end of wrapper -->


		<?php include("includes/footer.php"); ?>

		<?php
		if(isset($_GET['welcome'])){
			include("includes/welcome.php");
		}
		?>

    </body>

</html>