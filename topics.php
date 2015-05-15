<?php
require("includes/setup.php");

if(isset($_COOKIE['user'])){
    $id = $_COOKIE['user'];
}
require("includes/encryption.php");
$decodedID = decrypt_id($id);

$json = file_get_contents(SITE_URL . "api/user.php?id=$decodedID");
$decoded = json_decode($json);

$name = $decoded->name;
if($decoded->image == ""){
    $image = "<img src='".SITE_URL."images/noProfile.jpg' id='profilePic'/>";
    $postImage = "<img src='".SITE_URL."images/noProfile.jpg'/>";
}else{
    $image = "<img src='$decoded->image' id='profilePic' />";
    $postImage = "<img src='$decoded->image' />";
}
$about = $decoded->about;


$notificationsAPI = file_get_contents(SITE_URL . "api/notifications.php?id=$decodedID");
$notifications = json_decode($notificationsAPI);

$conversationsAPI = file_get_contents(SITE_URL . "api/conversations.php?user=$decodedID");
$conversationsDecoded = json_decode($conversationsAPI);


#process the form
if( isset($_POST['topic'])){
	$topic = $_POST['topic'];
	$statement = $db->prepare("INSERT INTO suggested_topics(user_id, topic) VALUES(?, ?)");
	$statement->bind_param("is", $decodedID, $topic);
	$statement->execute();
}

include("includes/functions.php");

?>

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/favorites.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>     
    
    <body>
    	<?php include_once("analyticstracking.php") ?>
        <div id="wrapper">
            
            <div id="mainHeader">
                <img src="images/logo2.png"/>
            </div>
            
            
            <div id="sidebar" data-user-id="<?php echo $id; ?>" data-user-name="<?php echo $name;?>" data-user-pic="<?php echo $postImage;?>">
                
                <div id="userBlock" class='inboxUser' <?php if($id==null){ echo "style='display:none;'"; }?>>
                
                
                	<?php include("includes/profile.php"); ?>
                    
                </div>     
                
                <div class="section">
                    <h3 class="sectionHeader">Favorited Topics</h3>
                    <ul id="userTopics">
                        <?php
	                    echo "<li><a href='".SITE_URL."app.php'>all</a></li>";
                        $userInterests = array();
                        foreach($decoded->interests as $interest){
                            $interestID = $interest->id;
                            array_push($userInterests, $interestID);
                            $interestName = $interest->interest;
                            echo "<li style='border-color: ". colorTag($interestID) .";'><a href='".SITE_URL."app.php?i=$interestID'>$interestName</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>";
                        }
                        ?>
                    </ul>
                </div>    
                
                <!-- this needs to be determined through php and filter out the ones that are already favorited by a user -->
                <!--
                <h3 class="sectionHeader">Popular Topics</h3>
                <ul id="userTopics">
                        <li><a href='app.php?i=1'>design</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>
                        <li><a href='app.php?i=2'>beer</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>
                        <li><a href='app.php?i=3'>fashion</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>
                        <li><a href='app.php?i=4'>gaming</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>
                        <li><a href='app.php?i=5'>web development</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>
                        <li><a href='app.php?i=6'>social media</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>
                    </ul>
                
                <div style="padding: 20px; clear:both;"></div>
                -->
            </div>
            <!-- end of sidebar -->
            
            
            <!-- ------------------------------------ start of the Main Content --------------------------------- -->
            
            <div id="main">
            	
            	<?php
            	if(isset($_POST['topic'])){
	            	if( $statement){ 
						echo "<h3 class='sectionHeader'>Your Topic was submitted!</h3>";
						echo "<p>Thank you for submitting! Your topic was submitted for reviews. You will be notified soon if you're topic has been approved.</p>";
					}else{
						echo "<h3 class='sectionHeader'>Something Went Wrong!</h3>";
						echo "<p>You're topic was not submitted due to an error on our end. If the problem continues please contact us so we can fix it.</p>";
					}
            	}
            	
				
				?>
            
            	<h3 class='sectionHeader'>All Topics</h3>
            	
            	<ul id="allTopics">
                    <?php
                    $interestsQ = $db->query("SELECT * FROM interests");
                    while($iRow = $interestsQ->fetch_assoc() ){
                        $thisInterest = $iRow['interest'];
                        $thisInterestId = $iRow['id'];
                        if(!in_array($thisInterestId, $userInterests)){
                            echo "<li><a href='app.php?i=$thisInterestId'>$thisInterest </a>";
                            if(isset($_COOKIE['user'])){
                                echo "<span class='addFav' data-topic-id='$thisInterestId'>[+]</span></li>";
                            }else{
                                echo "<span class='addFav' data-topic-id='$thisInterestId' style='display:none;'>[+]</span></li>";
                            }
                        }else{
                            #echo "test";
                        }
                    }
                    ?>
                </ul>
                
                <div style="padding: 20px; clear:both;"></div>
                
                <h3 class="sectionHeader">Create New Topic</h3>
                <?php if(isset($_COOKIE['user'])){ ?>
                
	                <form action="topics.php" method="post">
	                <label>Topic Name: </label> <input type="text" name="topic" id="topicBox">
	                <input type="submit" value="SUBMIT TOPIC" id="createTopicButton">
	                </form>
	            <?php
                }else{
                ?>
                
                <p>Only users who are signed in can create new topics</p>
                
                <?php } ?>
                

            </div>
            
				
				<!-- -------------------------------------------------------------
                Start of news area
                -------------------------------------------------------- -->
            
            <div id="right" class="topicsSide">
            	
            	<h3 class="sectionHeader">What are topics?</h3>
                <p>VanCity Social posts are organized by topics so you can more easily connect with like-minded people. So for example, if you want to read about restaurants you can browse posts in the restaurants topic.</p>
                
                <p>When creating a post it's a good idea to pay attention to the topic you're posting to.</p>
                
                <h3 class="sectionHeader">Can I create a new topic?</h3>
                <p>All users are welcome to create whatever new topics they like. The possibilities are endless.
                You could create a topic for a specific hobby you and your friends are interested in, for an upcoming event, or whatever else you can think of.
                It doesn't matter how specific or vague it is. If you think other people would be interested in the same topic, go for it.</p>
                
            </div>
				
            <!--
            <div id="notificationBox"></div>
            -->
            
        </div> <!-- end of wrapper -->
        
        <?php include("includes/footer.php"); ?>
        
    </body>
    
</html>

<?php
#include("includes/updateInboxStatus.php");
?>