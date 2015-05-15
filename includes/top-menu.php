
<div class="top-menu menu-image"><?php echo $image; ?></div>
<div class="top-menu"><span><?php echo $name; ?></span></div>

<div class="top-menu">
	<div class="user-menu"><a href='friends.php'>Friends</a></div>
	<div class="user-menu"><a href='notifications.php'>Notifications</a></div>
	<div class="user-menu"><a href='inbox.php'>Inbox</a></div>
	<?php if( basename($_SERVER['PHP_SELF']) != "app.php"){ echo "<div class='user-menu'><a href='app.php'>Home</a></div>"; } ?>
</div>

