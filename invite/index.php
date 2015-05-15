<?php

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

$thisKey = $_GET['invite_key'];

$q = $db->query("SELECT * FROM users WHERE password='$thisKey'");

while($row = $q->fetch_assoc()){
	$thisName = $row['name'];
	$thisImage = $row['image'];
	$img = "<img src='" . $thisImage . "'/>";
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
  <title>Welcome to VanCity Social</title>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <link href='http://fonts.googleapis.com/css?family=Bevan' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,600,600italic' rel='stylesheet' type='text/css'>
  <style type="text/css">
    html, body{
	    margin: 0; padding: 0; height: 100%;
    }
    body{
    	/*
	    background: no-repeat center center fixed;
	    background-image: url(../images/invitation1.jpg);
	    background-size: cover;
	    position: absolute;
	    top:0;
	    right:0;
	    left:0;
	    bottom:0;
	    */
	    font-family: 'Bevan', cursive;
	    font-family: 'Open Sans', sans-serif;
	    font-weight: 800;
	    background-color: #f59c1e;
	    background-color: #f5ad21;
    }
    
    p{
	    /* text-transform: uppercase; */
	    font-size: 3em;
	    padding-left: 50px;
	    color: #eb2c31;
    }
    .white{
	    color:white;
    }
    .extra{
	    font-size: 1.5em;
	    font-weight: 600;
	    color: white;
	    margin-bottom: 100px;
	    width: 80%;
    }
    .fullscreen{
	    height: 95%;
	    background-color: #f5ad21;	    
    }
    #sell{
	    background: #3c0091;
	    color: #eb2c31;
	    padding-top: 100px;
	    padding-bottom: 200px;
    }
    .logo{
	    margin-top: 200px;
	    /* border-top: 1px solid white; */
	    padding-top: 20px;
	    margin-left:50px; margin-right: 50px;
	    padding-left: 20px;
    }
    #firstSocial{
	    font-size: 1.8em;
	    max-width: 400px;
	    border-top: 1px solid white;
	    font-weight: 600;
	    font-style: italic;
	    margin-left: 50px;
	    padding-left: 0;
	    padding-top: 10px;
	    margin-top:-30px;
    }
  </style>
</head>


<body>
  	<?php #echo $thisName; ?>
  	<?php #echo $img; ?>
  	
  	<!--
  	<img src="/images/logo.png" id="logo"/>
  	-->
  	<div class="fullscreen">
	  	<p>Hello <span class='white'><?php echo $thisName; ?></span>, <br/>
	  	You've been invited to<br/>
	  	<span class='white'>VanCity Social</span><p>
	  	
	  	<p id="firstSocial">The first social network made<br/>
	  	entirely for Vancouver.</p>
	  	
	  	<p class='logo'><img src="/images/logo.png"/></p>
	  	<!--
	  	
	  	----------
	  	the first social network made
	  	entirely for Vancouver.
	  	
	  	-----------------------------
	  	logo   learn more     sign up
	  	-->
	  	
  	</div>
  	
  	<div id="sell">
	  	<p>Meet new people.</p>
	  	<p class='extra'>VanCity Social organizes posts by topics rather than friendships, so there's always a chance to interact with new people. Choose your favourite topics and connect with like-minded people throughout the city.</p>
	  	
	  	<p>Stay up to date <br/>with local news and events.</p>
	  	<p class='extra'>The News Feed will show you a stream of the latest posts from Vancouver most followed and trusted blogs. Reading story summaries and leaving comments are easy. Other users will see your comments in their feed.</p>
	  	
	  	<p>Enjoy clutter-free, <br/>relevant content.</p>
	  	<p class='extra'>No game invites. No business pages. No apps. No spam. Because VanCity Social is an invite-only network, you can count on content staying on-topic.</p>
	  	
  	</div>
  	
  	
  	<div id="signup">
  	
  	</div>
  	
</body>
</html>