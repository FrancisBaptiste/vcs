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
        <link href='http://fonts.googleapis.com/css?family=Roboto:700,300,400' rel='stylesheet' type='text/css'>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/notifications.js"></script>
        <script type="text/javascript" src="js/comments.js"></script>
        <script type="text/javascript" src="js/favorites.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/news_popup.js"></script>
    </head>     
    
    <body id="splash-body">
    	<?php include_once("analyticstracking.php") ?>
    	
    	<div id="fullMask"></div>
    	
        <div id="wrapper">
            
            <div id="mainHeader">
                <img src="images/logo2.png"/>
                <?php if($id == null){ 
	                echo "<div id='loginButton'>Member log in</div>";
	                echo "<div id='buttonDivide'> | </div>";
	                echo "<div id='redeemButton'>Redeem invite code</div>";
	            }else{
		            include("includes/top-menu.php");
		        }
	            ?>
            </div>
           
        </div> <!-- end of wrapper -->
        
        
        <div id="splash">
	        <h2>a new social network<br/>just for <span>Vancouver</span></h2>
        </div>
        
        <div id="splash-features">
	        <div class="splash-feature-block">
		        <div class="splash-feature-content">
			        <h4>meet new people in your city</h4>
			        <p>Posts are organized by topics rather than friendships, so there's always a chance to interact with new people.</p>
		        </div>
	        </div>
	        
	        <div class="splash-feature-block">
		        <div class="splash-feature-content">
			        <h4>latest local news and events</h4>
			        <p>The News Feed will show you a stream of the latest posts from Vancouver's most followed and trusted blogs.</p>
		        </div>
	        </div>
	        
	        <div class="splash-feature-block">
		        <div class="splash-feature-content">
			        <h4>clutter-free, relevant content</h4>
					<p>No game invites. No business pages. No spam. VanCity Social is an invite-only network, so content is always on-topic.</p>
		        </div>
	        </div>

        </div>
        
        
        <div id="splash-invite">
	        <div class="splash-50 left-50">
		        <div class="splash-50-inside">
			        <a href="#">I <span>have</span> an invite code</a>
		        </div>
	        </div>
	        <div class="splash-50 right-50">
		        <div class="splash-50-inside">
			        <a href="#">I <span>want</span> an invite code</a>
		        </div>
	        </div>
        </div>
        
        
        <div id="splash-footer">
	        <div>Join Our Mailing List</div>
	        <ul>
		        <li>Privacy Notice</li>
		        <li>Terms of Use</li>
		        <li>About Us</li>
	        </ul>
	        <div id="copyright">Copyright &copy;2015 VanCitySocial.ca</div>
        </div>
        
		<?php #include("includes/footer.php"); ?>
		
		
    </body>
    
</html>