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

?>

<!DOCTYPE html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/messages.js"></script>
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

            <div id="sidebar" data-user-id="<?php echo $id; ?>" data-user-name="<?php echo $name;?>" data-user-pic="<?php echo $postImage;?>">

                <?php #include("includes/profile.php"); ?>

                <div id="allConversations">
                	<h3 class="sectionHeader">Conversations</h3>
                	<ul id="userFriendlist">
                		<?php
                		foreach($conversationsDecoded as $conversation){
                			if($conversation === reset($conversationsDecoded)){
	                			$topConversation = $conversation->id;
                			}
	                		echo "<li data-conversation-id='". $conversation->id ."'><span class='friendImage' style='margin-right: 10px; position:relative; top: 5px;'>";
	                		echo "<img src='".$conversation->partner->image."' width='20' height='20'/></span>";
	                		echo $conversation->partner->name;
	                		if( $conversation->inbox == 1 ){
		                		echo "<span class='unread'>New</span>";
	                		}
	                		echo "</li>";
                		}
                		?>
                	</ul>
                </div>

            </div>
            <!-- end of sidebar -->


            <!-- ------------------------------------ start of the Main Content --------------------------------- -->

            <?php
    		foreach($conversationsDecoded as $conversation){
    			if($conversation === reset($conversationsDecoded)){
        			$topConversation = $conversation->id;
    			}
    		}
    		?>

            <div id="main">
            	<h3 class='sectionHeader'>Messages</h3>
		<div id="allMessages" data-conversation-id="<?php if(isset($_GET['conversation'])){echo $_GET['conversation']; }else{ echo $topConversation; } ?>">
		    <?php
		    #this is where the message will go
		    #do an api call here and populate the message part
		    if( isset($_GET['conversation'])){
				$messagesURL = SITE_URL . "api/messages.php?id=" . $_GET['conversation'];
			}else{
				$messagesURL = SITE_URL . "api/messages.php?id=" . $topConversation;
			}
		    $messagesAPI = file_get_contents($messagesURL);
		    $messagesDecoded = json_decode($messagesAPI);

		    foreach($messagesDecoded as $message){
		    ?>

		    <div class="message post">
			<?php
			    echo "<div class='picMask'><img src='". $message->user->image ."'/></div>";
			?>
			<p><strong><?php echo $message->user->name; ?></strong> <?php echo stripslashes($message->text); ?> <em><?php echo $message->date; ?></em></p>
		    </div>

		    <?php
		    }
		    ?>
                </div> <!-- end of #allMesssages -->

				<div id="submit">
                    <textarea name="messageInbox" id="messageInbox" class="inboxMess" cols="25" rows="5"></textarea>
                    <div id="messageButtonInbox">Send Message</div>
                </div>



            </div>


				<!-- -------------------------------------------------------------
                Start of news area
                -------------------------------------------------------- -->

            <div id="right">
                <?php include("includes/lookup.php"); ?>
            </div>


        </div> <!-- end of wrapper -->


        <?php include("includes/footer.php"); ?>


    </body>

</html>

<?php
include("includes/updateInboxStatus.php");
?>