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

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script type="text/javascript" src="js/news.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
    </head>     
    
    <body style="margin:0; background: url(images/tileBright.jpg) repeat-x;">
    	<?php include_once("analyticstracking.php") ?>
        <div id="wrapperNews">
            
            <div style="margin-bottom: 50px;text-align:center;">
                <img src="images/headerBright.jpg"/>
            </div>
            
            <div id="description">A social network for Vancouver</div>
            <div id="homeLink"><a href="app.php"></a></div>
            
            <div id="backHome"><a href="app.php">&#8592 Back to Main Timeline</a></div>
            <div id="spaceFixer" style="margin-bottom: 20px;">&nbsp;</div>
			
			
			
            <div id="sidebar" user_id="<?php echo $id; ?>" user_name="<?php echo $name;?>" user_pic="<?php echo $postImage;?>">
                
                <!--
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
                -->
                
                <div id="userBlock" <?php if($id==null){ echo "style='display:none;'"; }?>>
                    <?php echo $image; ?>
                    <h2><?php echo $name; ?></h2>
                    <!--
                    <p id="editProfile">edit profile</p>
                    -->
                </div>         
                
                <div id="notificationsLogout" <?php if(!isset($_COOKIE['user'])){ echo "style='display:none;'";} ?>>
                    <a href="logout.php">Logout</a>
                </div>
                
		
		<?php
		include("includes/forms.php");
		?>
		
		<p>The VancitySocial Newsfeed pulls from the top blogs and news sources for the Vancouver area, including the following publishers:</p>
		<ul>
		    <li>The Vancouver Sun</li>
		    <li>The Georgia Straight</li>
		    <li>Vancity Buzz</li>
		    <li>Miss 604</li>
		    <li>Inside Vancouver</li>
		    <li>604 Now</li>
		    <li>Metro News Vancouver</li>
		    <li>24 Hours Vancouver</li>
		    <li>And More...</li>
		</ul>
		
		
            </div>
            <!-- end of sidebar -->
            
            
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
	    
	    <div id="currentStory" story_id="<?php echo $storyID; ?>">
			<h2><?php echo stripslashes($title); ?></h2>
			<img src="<?php echo $image;?>" id="storyImage"/>
			<p><?php echo stripslashes($excerpt); ?></p>
			<p id="fullStory"><a href="<?php echo $link;?>" target="blank"/>Click here to read the full story from <?php echo $source;?>.</a></p>
			<p class="storyDetails">Published: <?php echo $date; ?></p>
			<p class="storyDetails">Story from <em><a href='<?php echo $link;?>' target="blank"><?php echo $source;?></a></em></p>
			<p class="storyDetails">Writer Credit: <em><?php echo $writerC; ?></em></p>
			<p class="storyDetails">Photo Credit: <em><?php echo $photoC; ?></em></p>
			
			
			<h3>Leave a Comment</h3>
			
			<div id="submit">
			    <?php
			    if($id == null){
				echo '<br/>Guest Name:<br/><input type="text" id="guestName"/>';
			    }
			    ?>
			    <textarea name="comment" id="comment" cols="25" rows="5"></textarea>
			    <div id="commentButton">Comment</div>
	        </div>
			
			<h3 id="commentsHeader">Comments</h3>
			<div id="newsComments">
			    <?php
			    include("includes/newsComments.php");
			    ?>
			</div>
	    </div>
	    
	    
	    <div id="latestNewsfeed">
		<h3>Latest From The Newsfeed</h3>
				<?php
				$nq = $m->query("SELECT * FROM news ORDER BY id DESC LIMIT 15");
				while($news = $nq->fetch_assoc()){
                    echo "<div class='story'>";
                    echo "<div class='newsImageMask'><a href='news.php?story=$news[id]' target='blank'><img src='$news[image]' style='width:100%;' /></a></div>";
                    echo "<h4><a href='news.php?story=$news[id]'>". stripslashes($news[title]) . "</a></h4>";
                    echo "</div>";
                }
                ?>
	    </div>
	    
	    
	    <?php
	    }else{
	    ?>
            	<h2>Vancouver News Feed</h2>
                <div id="allStories">
                <?php
		$nq = $m->query("SELECT * FROM news ORDER BY id DESC LIMIT 40");
		while($news = $nq->fetch_assoc()){
                    echo "<div class='story'>";
                    echo "<div class='newsImageMask'><a href='$news[link]'><img src='$news[image]' style='width:100%;' /></a></div>";
                    echo "<h4><a href='news.php?story=$news[id]'>". stripslashes($news[title]) . "</a></h4>";
                    echo "</div>";
                }
                ?>
                </div>
            <?php
	    }
	    ?>
            </div>
	    
            <!-- -------------------------------------------------------------
                Start of news area
                -------------------------------------------------------- -->
            

            
        </div> <!-- end of wrapper -->
        
		
		<!-- popup for the message user box -->
		
		<div id="messageUserMask">
			<div id="messageUserBox">
				<div id="messageHeader">
					Message User Directly
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
		
    </body>
    
</html>

<?php
#include("includes/updateInboxStatus.php");
?>