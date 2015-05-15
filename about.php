<?php
$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

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

$notificationsAPI = file_get_contents("http://vancitysocial.ca/api/notifications.php?id=$decodedID");
$notifications = json_decode($notificationsAPI);

?>

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/notifications.js"></script>
    </head>     
    
    <body style="margin:0; background: url(images/tileBright.jpg) repeat-x;">
    	<?php include_once("analyticstracking.php") ?>
        <div id="wrapper">
            
            <div style="margin-bottom: 50px;">
                <img src="images/headerBright.jpg"/>
            </div>
            
            <div id="description">A social network for Vancouver</div>
            <div id="homeLink"><a href="app.php"></a></div>
            <div id="backHome"><a href="app.php">&#8592 Back to Main Timeline</a></div>
            <div id="spaceFixer" style="margin-bottom: 5px;">&nbsp;</div>
            
            
            <div id="sidebar" user_id="<?php echo $id; ?>" user_name="<?php echo $name;?>" user_pic="<?php echo $postImage;?>">
                
                <div id="userBlock" class='inboxUser' <?php if($id==null){ echo "style='display:none;'"; }?>>
                	<div class="section">
                		<h3 class="sectionHeader">Profile</h3>
            			<?php echo $image; ?>
						<h2><?php echo $name; ?></h2>
						
						<ul style="clear:both;">
	                    	<li><a href="inbox.php">Inbox</a></li>
	                    	<li><a href="lookup.php">User Lookup</a></li>
	                    	<li><a href="logout.php">Logout</a></li>
	                    </ul>
                	</div>
                </div>         
                    
            </div>
            <!-- end of sidebar -->
            
            
            <!-- ------------------------------------ start of the Main Content --------------------------------- -->
            
            <div id="main">
            	<h3 class='sectionHeader'>About</h3>
            	
            	
            	
            </div>
            
            
            <div id="right">
                <h3 class='sectionHeader'>Something</h3>
                
            </div> 
				
        </div> <!-- end of wrapper -->
        
        <div id="notificationMask">
        	<div id="notificationBox"></div>
        </div>
        
    </body>
    
</html>

<?php
include("includes/updateInboxStatus.php");
?>