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
        <link rel="stylesheet" type="text/css" href="styles-mobile.css">
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

			<div id="sidebar" data-user-id="<?php echo $id; ?>" data-user-name="<?php echo $name;?>" data-user-pic="<?php echo $postImage;?>">
			</div>


            <!-- -------------------------------------------------------------
                Start of news area
                -------------------------------------------------------- -->
			<?php
			if($_GET['page'] == "news"){
			?>
            <div id="mobileNews">
                <h3 class='sectionHeader'>News <?php # if($i != 0 && $i != 1){ echo ucwords($currentInterest);} ?></h3>
                <?php
	            if($i != 0 && $i != 1){
		            $newsQ = $db->query("SELECT * FROM news WHERE topic=$i ORDER BY id DESC LIMIT 10");
	            }else{
		            $newsQ = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT 10");
	            }
                while($news = $newsQ->fetch_assoc() ){
                    echo "<div class='newsCard'><a href='mobile.php?story=".$news[id]."'><img src='news_images/$news[image]' style='width:100%;' class='newsImage' data-news-id='".$news[id]."'/></a>";
                    #echo "<h4><a href='news.php?story=$news[id]'>" . stripslashes($news[title]) . "</a></h4>";
                    echo "<h4 class='newsTitle' data-news-id='".$news[id]."'><a href='mobile.php?story=".$news[id]."'>" . stripslashes($news[title]) . "</a></h4></div>";
                }
                ?>
            </div>
            <?php
	        }
	        ?>



	        <!-- -------------------------------------------------------------
                Start of news story area
                -------------------------------------------------------- -->
	        <?php
			if(isset($_GET['story'])){
			?>
			<div id="newsBox">
				<p id="newsBack"><a href="mobile.php?page=news">Back to News</a></p>
		        <div id="wrapperNews">
		            <div id="mainNews">

				    <?php
					$storyID = $_GET['story'];
					$q = $db->query("SELECT * FROM news WHERE id=$storyID");
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
							<img src="news_images/<?php echo $image;?>" id="storyImage"/>
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
					    </div><!-- end of currentStory -->
		            </div><!-- end of mainNews -->
	        	</div> <!-- end of wrapper -->
			</div><!-- end of newsBox -->
	        <?php
		    }
		    ?>





        </div> <!-- end of wrapper -->


		<?php
			include("includes/mobile-nav.php");
		?>


    </body>

</html>