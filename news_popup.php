<?php
$m = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

if(isset($_COOKIE['user'])){
    $id = $_COOKIE['user'];
}
require("includes/encryption.php");
$decodedID = decrypt_id($id);

$json = file_get_contents("http://vancitysocial.ca/api/user.php?id=$decodedID");
$decoded = json_decode($json);

$name = $decoded->name;
if($decoded->image == ""){
    $image = "<img src='http://vancitysocial.ca/images/noProfile.jpg' id='profilePic'/>";
    $postImage = "<img src='http://vancitysocial.ca/images/noProfile.jpg'/>";
}else{
    $image = "<img src='$decoded->image' id='profilePic' />";
    $postImage = "<img src='$decoded->image' />";
}
$about = $decoded->about;


?>

<!--
add this to main
<script type="text/javascript" src="js/news.js"></script>
-->


        <div id="wrapperNews">
            
            <!-- ------------------------------------ start of the Main Content --------------------------------- -->
            <div id="mainNews">
		
	    <?php
	    if(isset($_GET['story'])){
			$storyID = $_GET['story'];
			$q = $m->query("SELECT * FROM news WHERE id=$storyID");
			while($story = $q->fetch_assoc()){
				$title = $story['title'];
				$date = $story['date'];
				$source = $story['source'];
				$link = $story['link'];
				$excerpt = $story['excerpt'];
				$image = $story['image'];
				$photoC = $story['photo_credit'];
				$writerC = $story['writer_credit'];
			}
		
	    ?>
	    
	    <div id="currentStory" data-story-id="<?php echo $storyID; ?>">
			<h2><?php echo stripslashes($title); ?></h2>
			<img src="<?php echo $image;?>" id="storyImage"/>
			<p><?php echo stripslashes($excerpt); ?></p>
			<p id="fullStory"><a href="<?php echo $link;?>" target="blank">Click here to read the full story from <?php echo $source;?>.</a></p>
			<p class="storyDetails">Published: <?php echo $date; ?></p>
			<p class="storyDetails">Story from <em><a href='<?php echo $link;?>' target="blank"><?php echo $source;?></a></em></p>
			<!--
			<p class="storyDetails">Writer Credit: <em><?php echo $writerC; ?></em></p>
			<p class="storyDetails">Photo Credit: <em><?php echo $photoC; ?></em></p>
			-->
			
			<div id="newsCommentSubmit">
				<h3>Leave a Comment</h3>
				<div id="submit">
					<textarea name="comment" id="comment" cols="25" rows="5"></textarea>
					<div id="commentButton">Comment</div>
				</div>
			</div>
				
			
			<!--
			<h3 id="commentsHeader">Comments</h3>
			-->
			
			<div id="newsComments">
			    <?php
				#add here a list of comments
				#the first three comments of the story, and a total comment count
				#then a link to the story page
				#story page will be a timeline page ?story=ID
				#instead of a topic it will say "Comments on news aricle 'somethign soemething'"
				#if you leave a comment on this page it gets added to the news articles comments
				/*
				include("includes/newsComments.php");
				
				$commentsQ = $m->query("SELECT * FROM news_comments WHERE story_id=$storyID");
				while($c = $commentsQ->fetch_assoc()){
					echo "<p>". $c['text'] . "</p>";
				}
				*/
			    ?>
			</div>
			
	    </div>
	    
	    
	    
	    <?php
	    }
	    ?>
            </div>

            
        </div> <!-- end of wrapper -->
        
