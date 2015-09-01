<?php

require("../includes/setup.php");
#$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");


$postIndex = $_POST['postIndex'];

if(isset($_POST['i'])){
    $i = $_POST['i'];
    $content = file_get_contents(SITE_URL."api/posts.php?interest=$i&postIndex=$postIndex");
}else{
    $i = 0;
    $content = file_get_contents(SITE_URL."api/posts.php?postIndex=$postIndex");
}
$contentDecoded = json_decode($content);


include("../includes/functions.php");

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

		<div class="picMask"><img src="<?php echo $post->user_image; ?>"/></div>

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

			if($post->interest == 41){
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
					include("../includes/timeFormat.php");
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
                include("../includes/timeFormat.php");

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
