<div id="mobileNav" style="display:none;">
	<div id="mobileNavHeader">
		<h3>Main Feed</h3>
		<img src="images/nav-icon.jpg" alt="mobile nav icon" />
	</div>
	<ul>
		<li><a href="app.php">Home</a></li>
		<li><a href="mobile.php?page=news">News</a></li>
		<li><a href="mobile-inbox.php">Inbox</a></li>
		<li><a href="notifications.php">Notifications</a></li>
		<li><a href="#">Friends</a></li>
		<li><a href="#">Account</a></li>
	</ul>
</div>

<script>
$(function(){
	$("#mobileNavHeader").click(function(){
		if($("#mobileNav ul").css("display") == "none"){
			$("#mobileNav ul").slideDown();
		}else{
			$("#mobileNav ul").slideUp();
		}
	});
});
</script>