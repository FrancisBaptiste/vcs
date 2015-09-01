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
    $image = "<img src='http://vancitysocial.ca/images/noProfile.jpg' id='profilePic'/>";
    $postImage = "<img src='http://vancitysocial.ca/images/noProfile.jpg'/>";
}else{
    $image = "<img src='$decoded->image' id='profilePic' />";
    $postImage = "<img src='$decoded->image' />";
}
$about = $decoded->about;


$notificationsAPI = file_get_contents(SITE_URL . "api/notifications.php?id=$decodedID");
$notifications = json_decode($notificationsAPI);


?>

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" type="text/css" href="styles-mobile.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/notifications.js"></script>
        <script type="text/javascript" src="js/comments.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript" src="js/news_popup.js"></script>
    </head>

    <body>
    	<?php include_once("analyticstracking.php") ?>
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



            <!-- ------------------------------------ start of the Main Content --------------------------------- -->

            <div id="main" style="width:100%;">

            	<h3 class='sectionHeader'>Notification History</h3>
            	<?php
            	foreach($notifications as $n){
                           echo "<div class='notificationAlert notificationPageItem' data-fetch-pid='$n->post_id' data-nid='$n->id'><span>$n->commenter_name</span> commented on something you posted.</div>";
                }
                ?>

            </div>



        </div> <!-- end of wrapper -->

        <?php include("includes/footer.php"); include("includes/mobile-nav.php"); ?>

		<div id="sidebar" data-user-id="<?php echo $id; ?>" data-user-name="<?php echo $name;?>" data-user-pic="<?php echo $postImage;?>"></div>

    </body>

</html>

<?php
include("includes/updateInboxStatus.php");
?>