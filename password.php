<?php
require("includes/setup.php");


if(isset($_COOKIE['user'])){
    $id = $_COOKIE['user'];
}else{
    header('Location: '.SITE_URL . 'index.php');
}

require("includes/encryption.php");
$decodedID = decrypt_id($id);

$json = file_get_contents(SITE_URL . "api/user.php?id=$decodedID");
$decoded = json_decode($json);

$name = $decoded->name;
if($decoded->image == ""){
    $image = "<img src='".SITE_URL . "images/noProfile.jpg' id='profilePic'/>";
    $postImage = "<img src='".SITE_URL . "images/noProfile.jpg'/>";
}else{
    $image = "<img src='$decoded->image' id='profilePic' />";
    $postImage = "<img src='$decoded->image' />";
}
$about = $decoded->about;


$notificationsAPI = file_get_contents(SITE_URL . "api/notifications.php?id=$decodedID");
$notifications = json_decode($notificationsAPI);

$conversationsAPI = file_get_contents(SITE_URL . "api/conversations.php?user=$decodedID");
$conversationsDecoded = json_decode($conversationsAPI);


if(isset($_POST['oldPassword'])){
	$oldPassword = $_POST['oldPassword'];
	$newPassword = $_POST['newPassword'];
	$retypePassword = $_POST['retypePassword'];
	
	$q = $db->query("SELECT password FROM users WHERE id='$decodedID'");
	
	$message = "";
	
	while($row = $q->fetch_assoc()){
		$thisPass = $row['password'];
	}
	
	if($oldPassword == $thisPass){
		if($newPassword != $retypePassword){
			$message = "The two passwords didn't match. $newPassword $oldPassword";
		}else{
			if($newPassword == ""){
				$message = "New password can't be blank.";
			}else{
				$statement = $db->prepare("UPDATE users SET password=? WHERE id=$decodedID");
				$statement->bind_param("s", $newPassword);
				$done = $statement->execute();
				if($done){
					$message = "Password has been updated to '$newPassword'.";
				}
			}
		}
	}else{
		$message = "That's not your old password.";
	}

}

?>

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/messages.js"></script>
        <script type="text/javascript" src="js/news_popup.js"></script>
        <script type="text/javascript" src="js/lookup.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
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
            
            <div id="newPassMessage">
	            <?php 
		            echo $message . "<br/><br/>";
	            ?>
            </div>
            
            <div id="passwordResetForm">
	            <form action="password.php" method="post" enctype="multipart/form-data">
		            Old Password:<br/>
		            <input type="text" name="oldPassword" class="passwordResetInput"/><br/><br/>
		            New Password:<br/>
		            <input type="password" name="newPassword" class="passwordResetInput"/><br/><br/>
		            Re-Type New Password:<br/>
		            <input type="password" name="retypePassword" class="passwordResetInput"/>
		            <input type="hidden" name="id" value="<?php echo $id; ?>">
		            <br/><br/>
		            <input type="submit" name="submit" value="Submit" class="editUserBtn" style="float:none;">
		        </form>
            </div>
            
				
            
        </div> <!-- end of wrapper -->
        
        
        <?php include("includes/footer.php"); ?>
        
        
    </body>
    
</html>

<?php
include("includes/updateInboxStatus.php");
?>