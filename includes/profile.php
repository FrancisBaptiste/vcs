<!-- not currently being used -->
<div id="userBlock" <?php if($id==null){ echo "style='display:none;'"; }?>>
	<h3 class="sectionHeader">Profile</h3>
    <?php echo $image; ?>
    <h2><?php echo $name; ?> <span id="editProfile">(edit)</span></h2>
    
    <ul>
    	<!-- <li id="editProfile">edit profile</li> -->
    	<li><a href="inbox.php">Inbox</a></li>
    	<li><a href="notifications.php">Notifications</a></li>
    	<li><a href="friends.php">Friends</a></li>
    	<?php if( basename($_SERVER['PHP_SELF']) != "app.php"){ echo "<li style='margin-top:20px;'><a href='app.php'>Main Timeline</a></li>"; } ?>
    </ul>
</div>