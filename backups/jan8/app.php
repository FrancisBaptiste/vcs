<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

require("includes/setup.php");

if(isset($_COOKIE['user'])){
    $id = $_COOKIE['user'];
}else{
    $id = null;
}

//include("includes/checkSignUp.php");
/*
if(!isset($_COOKIE['user'])){
    header("Location: index.php");
}
*/





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
    $content = file_get_contents("".SITE_URL."api/posts.php?interest=$i");
    $currentI = $db->query("SELECT interest FROM interests WHERE id=$i");
    #$currentInterest = $currentI->fetch_row();
    while($ci = $currentI->fetch_assoc() ){
	    $currentInterest = $ci['interest'];
    }
}else if(isset($_GET['list'])){
	$content = file_get_contents("".SITE_URL."api/posts.php?friendlist=$decodedID");
    $currentInterest = "FriendList";
}else{
    $i = 0;
    $content = file_get_contents("".SITE_URL."api/posts.php");
    $currentInterest = "All";
}
$contentDecoded = json_decode($content);

$notificationsAPI = file_get_contents("".SITE_URL."api/notifications.php?id=$decodedID");
$notifications = json_decode($notificationsAPI);

/*
function makeClickableLinks($s) {
	return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
} */
function makeClickableLinks($text){
	$newString = "";
    $words = explode(" ", $text);
    foreach($words as $word){
        if(strtolower(substr($word, 0, 4)) == "http" || strtolower(substr($word, 0, 3)) == "www"){
            if(strlen($word) >= 20){
                $newString .= "<a href='$word' target='blank' alt>$word</a> ";
            }else{
                $newString .= "<a href='$word' target='blank' alt>[Link]</a> ";
            }
        }else{
			$newString .= $word . " ";
		}
    }
	$newString = substr($newString, 0, -1);
	return $newString;
}
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
    </head>     
    
    <body style="margin:0; background: url(images/tileBright.jpg) repeat-x;">
    	<?php include_once("analyticstracking.php") ?>
    	
        <div id="wrapper">
            <!--
            if $newUser == true put some welome shit here
            -->
            <?php #include("includes/welcome.php"); ?>
            
            <div style="margin-bottom: 50px;">
                <img src="images/headerBright.jpg"/>
            </div>
            
            <div id="description">A social network for Vancouver</div>
            
            
            <div id="sidebar" user_id="<?php echo $id; ?>" user_name="<?php echo $name;?>" user_pic="<?php echo $postImage;?>">
                
                
                <?php include("includes/forms.php"); ?>
                
                
                <div id="editUser" style="display:none;">
                    <form action="functions/editProfile.php" method="post" enctype="multipart/form-data">
                        Select new image:
                        <input type="file" name="file" id="file"><br/><br/>
                        A bit about yourself:
                        <textarea name="about" rows="8"><?php echo $about; ?></textarea>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Submit">
                    </form>
                    <p id="cancelEdit">Cancel Edit</p>
                </div>
                
                
                <div id="userBlock" <?php if($id==null){ echo "style='display:none;'"; }?>>
                	<h3 class="sectionHeader">Profile</h3>
                    <?php echo $image; ?>
                    <h2><?php echo $name; ?></h2>
                    
                    <ul>
                    	<li id="editProfile">edit profile</li>
                    	<li><a href="inbox.php">Inbox</a></li>
                    	<li><a href="notifications.php">Notifications</a></li>
                    	<li><a href="lookup.php">User Lookup</a></li>
                    	<li><a href="app.php?list=friend">Friend List</a></li>
                    	<li><a href="logout.php">Logout</a></li>
                    </ul>
                </div>
                
				<div <?php if($id==null){ echo "style='display:none;'"; }?>>
					<h3 class="sectionHeader">Favorited Topics</h3>
					<ul id="userTopics">
						
						<?php
						echo "<li><a href='".SITE_URL."app.php'>all</a></li>";
						$userInterests = array();
						foreach($decoded->interests as $interest){
							$interestID = $interest->id;
							array_push($userInterests, $interestID);
							$interestName = $interest->interest;
							echo "<li><a href='".SITE_URL."app.php?i=$interestID'>$interestName</a> <span class='removeFav' topicId='$interestID'>[-]</span></li>";
						}
						?>
					</ul>
				</div>
                    
                <h3 class="sectionHeader"><?php if($id!=null){ echo "All"; }?>Topics</h3>
                <ul id="allTopics">
                    <?php
					$topicsLimit = 20;
                    $interestsQ = $db->query("SELECT * FROM interests LIMIT 20");
                    while($iRow = $interestsQ->fetch_assoc() ){
                        $thisInterest = $iRow['interest'];
                        $thisInterestId = $iRow['id'];
                        if(!in_array($thisInterestId, $userInterests)){
                            echo "<li><a href='".SITE_URL."app.php?i=$thisInterestId'>$thisInterest </a>";
                            if(isset($_COOKIE['user'])){
                                echo "<span class='addFav' topicId='$thisInterestId'>[+]</span></li>";
                            }else{
                                echo "<span class='addFav' topicId='$thisInterestId' style='display:none;'>[+]</span></li>";
                            }
                        }
                    }
					echo "<li><a href='".SITE_URL."topics.php'>View All Topics...</a>";
                    ?>
                </ul>
                
                   
            </div>
            <!-- end of sidebar -->
            
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
                            echo "<div class='notificationAlert notification' fetch_pid='$n->post_id' nid='$n->id'><span>$n->commenter_name</span> commented on something you posted.</div>";
                        }
                    }
					echo "</div>";
				}
				?>
            
        		<h3 id="topicsTop" class='sectionHeader'>Current Topic: <?php echo ucwords($currentInterest); ?><span id="dropDownArrow"></span></h3>
				<div id="topicsDropDown" style="display:none;">
					<ul id="dropDownTopics">
						<?php
						echo "<li><a href='".SITE_URL."app.php'>all</a></li>";
	                    $interestsQ = $db->query("SELECT * FROM interests");
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
                </div>
                
                <div id="posts" interest="<?php echo $i; ?>">
                
                <?php
                foreach($contentDecoded as $post){    
                ?>
                    <div class="post" post_id="<?php echo $post->id; ?>" user_id="<?php echo $post->user_id ?>">
                        <?php
                            echo "<div class='picMask'><img src='". $post->user_image ."'/></div>";
                        ?>
                        <div>
                        	<div class='userInformation'>
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
							
							if($post->interest == 41){
						    	$newsID = $post->news_id;
						    	$getTitle = $db->query("SELECT title FROM news WHERE id=$newsID");
						    	while($newsTitle = $getTitle->fetch_assoc() ){
							    	$thisTitle = $newsTitle['title'];
						    	}
							    $thisPost = "<span class='newsPost'>commented on the story <a href='".SITE_URL."news.php?story=" . $newsID . "'>$thisTitle</a>...</span> $thisPost";
						    }else if($post->interest == 42){
								$thisPost = "<span class='newsPost'>updated my 'about' blurb to...</span> $thisPost";
							}
							
                        	?>
	                        <p><strong class='userRollover' user_id='<?php echo $post->user_id; ?>'><?php echo $post->name; ?></strong> <span class='mainText'><?php echo $thisPost; ?></span></p>
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
                                
                                echo "<div><div class='userPopup'>click a user's name to send them a message</div></div><p><strong class='userRollover'>".$comment->user_name."</strong> <span class='mainText'>".$thisComment."</span> <em>".$postTime."</em></p>";
                                echo "</div>";
                            }
                            if($commentCount > $commentLimit){
	                            echo "<div class='viewAllComments'>View All Comments ($commentCount)</div>";
                            }
                        }
                        #echo "<span class=''></span><span class='addComment'>Add a Comment</span>";
                        
                        echo "<div class='actionLine'> ";
                        echo "<span class='addComment'>Add a Comment</span>";
                        echo "<span> | </span>";
						if(in_array($post->user_id, $friends)){
							echo "<span style='cursor:auto;'>Friends</span>";
						}else{
							echo "<span class='userButtonFriend'>Add As Friend</span>";
						}
                        echo "<span> | </span>";
                        echo "<span class='messageUser userButtonMessage'>Message User</span>";
                        echo "<span> | </span>";
                        #timing text logic
                        $postTime = strtotime($post->date);
                        
                        include("includes/timeFormat.php");
                        
                        $thisPostInterest = "all";
                        $postInterest = $post->interest;
                        $getInterest = $db->query("SELECT * FROM interests WHERE id=$postInterest");
                        while($pi = $getInterest->fetch_assoc()){
	                        $thisPostInterest = $pi['interest'];
                        }
                        echo "<span class='timePosted'>Posted to <a href='".SITE_URL."app.php?i=".$postInterest."'>'$thisPostInterest'</a> $postTime</span>";
                        echo "</div>";
                        
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
                <h3 class='sectionHeader'>News</h3>
                <?php
                $newsQ = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT 10");
                while($news = $newsQ->fetch_assoc() ){
                    echo "<img src='$news[image]' style='width:100%;' class='newsImage' data-news-id='".$news[id]."'/>";
                    #echo "<h4><a href='news.php?story=$news[id]'>" . stripslashes($news[title]) . "</a></h4>";
                    echo "<h4 class='newsTitle' data-news-id='".$news[id]."'>" . stripslashes($news[title]) . "</h4>";
                }
                ?>
            </div>            
        </div> <!-- end of wrapper -->
        
        <div id="notificationMask">
        	<div id="notificationBox"></div>
        </div>
        
        <!-- popup for the message user box -->
		
		<div id="messageUserMask">
			<div id="messageUserBox">
				<div id="messageHeader">
					<span id="messageHeaderText">Message User Directly</span>
					<div id="messageClose">X</div>
				</div>
				<div id="messageAlert" style="display:none;">Your message was sent.</div>
				<div id="submitDirect">
					<?php
					if($id == null){
					echo 'You can only send a direct message to a user if you are logged in.';
					}else{
					?>
					<p id="messageNote">*Note: Your message will be sent directly to this user's inbox.<br/>The auto-filled text is to give the receiver context.</p>
					<textarea id="message"></textarea>
					<div id="messageButton">Send Message</div>
										
					<?php } ?>
				</div>
			</div>
		</div>
        
        
		<div id="fixedFooter">
			<div><span id="footerContact">Contact</span> | <span id="footerAbout">About VanCity Social</span></div>
		</div>
		
		
		<div id="fullScreen"></div>
		
		<?php include("includes/aboutBox.php"); ?>
		
		
		<div id="newsBox">
			
		</div>
		
		<?php include("includes/invitation-request.php"); ?>
		
    </body>
    
</html>