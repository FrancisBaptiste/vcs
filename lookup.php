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
    $image = "<img src='".SITE_URL . "images/noProfile.jpg' id='profilePic'/>";
    $postImage = "<img src='".SITE_URL . "images/noProfile.jpg'/>";
}else{
    $image = "<img src='$decoded->image' id='profilePic' />";
    $postImage = "<img src='$decoded->image' />";
}
$about = $decoded->about;
$friendslist = $decoded->friendlist;

?>

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="js/lookup.js"></script>
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
	                    	<li><a href="notifications.php">Notifications</a></li>
	                    	<li><a href="logout.php">Logout</a></li>
	                    </ul>
                	</div>
                    
                </div>         
                
                    
            </div>
            <!-- end of sidebar -->
            
            
            <!-- ------------------------------------ start of the Main Content --------------------------------- -->
            
            <div id="main">
            	
            	<h3 class='sectionHeader'>User Lookup</h3>
            	
            	<input type="text" value="search name" id="userLookup"  style='width: 200px; padding: 10px;'/>
            	
                <ul id="allUsers">
                	<?php
                	
                	$q = $db->query("SELECT * FROM users");
                	while($row = $q->fetch_assoc() ){
	                	echo "<li class='userLine' image_path='" .$row['image'] . "' data-friend-id='".$row['id']."' style='display:none; margin-bottom: 20px;'>";
	                	echo "<span class='imageSpace' style='margin-right: 10px; position:relative; top: 5px;'></span><span class='name'>" . $row['name'] . "</span>";
	                	echo "<span class='sendUserMessage'>Send Message</span>";
	                	echo "<span class='addToFriendlist'>Add to Friendlist</span>";
	                	echo "</li>";
                	}
                	
                	?>
                </ul>
                
            </div>
            
            
            <div id="right">
                <h3 class='sectionHeader'>Friend List</h3>
                <ul id="userFriendlist">
                <?php
                $fq = $db->query("SELECT * FROM users WHERE id IN(" . $friendslist . ")");
				if($fq){
					while($friends = $fq->fetch_assoc() ){
						if($friends['image'] == "" || $friendImage == "undefined"){
							$friendImage = SITE_URL . "images/noProfile.jpg";
						}else{
							$friendImage = $friends['image'];
						}
						echo "<li data-friend-id='$friends[id]'><span class='friendImage' style='margin-right: 10px; position:relative; top: 5px;'>";
						echo "<img src='".$friendImage."' width='20' height='20'/>";
						echo "</span><span class='name'>" . $friends['name'] . "</span><span class='removeFriend'>X</span></li>";
					}
				}
					
                ?>
                </ul>
            </div> 
				
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
					<p id="messageNote">*Note: Your message will be sent directly to this user's inbox.</p>
					<textarea id="message"></textarea>
					<div id="messageButton">Send Message</div>
										
					
					<?php } ?>
				</div>
			</div>
		</div>
           
    </body>
    
</html>
