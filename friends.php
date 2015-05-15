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


$content = file_get_contents("".SITE_URL."api/posts.php?friendlist=$decodedID");
$contentDecoded = json_decode($content);


include("includes/functions.php");
?>

<!DOCTYPE html>
    <head>
    	<title>Vancity Social</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/lookup.js"></script>
        <script type="text/javascript" src="js/comments.js"></script>
        <script type="text/javascript" src="js/favorites.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>     
    
    <body>
	    
    	<?php include_once("analyticstracking.php") ?>
    	
    	<div id="fullMask"></div>
    	
        <div id="wrapper">
            
            <div id="mainHeader">
                <img src="images/logo2.png"/>
                <?php if($id == null){ 
	                echo "<div id='loginButton'>Log in / Sign up</div>"; 
	            }else{
		            include("includes/top-menu.php");
		        }
	            ?>
            </div>
            
            
            <div id="sidebar" data-user-id="<?php echo $id; ?>" data-user-name="<?php echo $name;?>" data-user-pic="<?php echo $postImage;?>">
                
                <?php #include("includes/forms.php"); ?>                
                <?php #include("includes/profile.php"); ?>
                <?php include("includes/friends-list.php"); ?>
                   
            </div>
            <!-- end of sidebar -->
            
            <!-- ------------------------------------ start of the Main Content --------------------------------- -->
            <div id="main" class="friendsMain">            	

        		
        		<h3 class='sectionHeader'>Friend Posts</h3>
                
                <div id="posts" interest="<?php echo $i; ?>">
                
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
							    #$thisPost = "<span class='newsPost'>commented on the story <a href='".SITE_URL."news.php?story=" . $newsID . "'>$thisTitle</a>...</span> $thisPost";
							    $thisPost = "<span class='newsPost'>commented on the story <a class='newsTitle' data-news-id='$newsID'>$thisTitle</a>...</span> $thisPost";

							    
						    }else if($post->interest == 42){
								$thisPost = "<span class='newsPost'>updated my 'about' blurb to...</span> $thisPost";
							}
							
                        	?>
                        	                        	
	                        <p>
		                        <strong class='userRollover' user_id='<?php echo $post->user_id; ?>'><?php echo $post->name; ?></strong> 
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
                                echo "</div>";
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
	            
	            <?php include("includes/lookup.php"); ?>
	            
            </div>            
        </div> <!-- end of wrapper -->
        

        
		<?php include("includes/footer.php"); ?>
		
		
		
    </body>
    
</html>