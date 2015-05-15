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
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,600' rel='stylesheet' type='text/css'>
  
  <link href="http://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed|Alegreya:700" rel="stylesheet" type="text/css" />
  <style type="text/css">
    html, body{
	    margin: 0; padding: 0; height: 100%;
    }
    body{
    	height: 100%;
	    background: no-repeat center center fixed;
	    background-image: url(../images/blur.jpg);
	    background-size: cover;
	    position: absolute;
	    top:0;
	    right:0;
	    left:0;
	    bottom:0;
    }
    
    .highlight{
	    /*color: #a9bcc3;
	    color: #446fa5;
	    color: #366db1; */
	    color: #3a88e7;
    }
    
    .fullscreen{
	    height: 100%;
    }
    .card{
	    max-width: 780px;
	    background: #f2f2e2;
	    color: #676758;
	    padding: 15px;
	    margin: 0 auto;
	    margin-top: 100px;
	    text-align: center;
    }
    .cardSmall{
	    max-width: 100px;
	    background: #f2f2e2;
	    color: #676758;
	    padding: 5px;
	    margin: 0 auto;
	    margin-top: 40px;
	    text-align: center;
	    opacity: 0.5;
	    -webkit-transition: opacity 1s;
	    -moz-transition: opacity 1s;
	    transition: opacity 1s;
	    cursor: pointer;
    }
    .cardSmall:hover{
	    opacity: 1;
    }
    .cardSmall p{
	    font-family: 'Roboto', sans-serif;
	    font-size: 0.7em;
    }
    .cardSmall p img{
	    opacity: 0.5;
    }
    .cardBorder{
	    border: 1px solid #676758;
    }
    .card p{
	    color: #676758;
	    font-size: 2em;
	    font-family: 'Alegreya', serif;
	    padding-left: 0;
    }
    .card p.firstSocial{
    	border-top: 1px solid #676758;
    	font-size: 1.2em;
	    font-family: 'Roboto Condensed', sans-serif;
	    width: 300px;
	    padding-top: 20px;
	    margin: 0 auto;
    }
    .card p.firstDetails{
	    font-family: 'Roboto', sans-serif;
	    font-size: 0.9em;
	    width: 300px;
	    margin: 0 auto;
	    margin-top: 20px;
    }
    .logo{
	    text-align: center;
	    opacity: 0.6;
	    margin-top: 50px;
	    margin-left: 0;
    }
    .card p.extraTitle{
	    font-family: 'Roboto Condensed', sans-serif;
	    font-size: 2em;
	    margin-bottom: 0;
    }
    .card p.extra{
    	font-family: 'Roboto', sans-serif;
	    font-size: 0.8em;
	    font-weight: 600;
	    margin-bottom: 100px;
	    width: 80%;
	    margin: 0 auto;
    }
    .spacer{
	    padding: 20px;
    }
    
    #password{
	    border: 1px solid #676758;
	    background: white;
	    padding: 10px;
	    color: #CCC;
	    margin: 20px 0;
	    text-align: center;
	    width: 200px;
	    font-family: 'Roboto', sans-serif;
    }
    
    #passwordBtn{
	    border: 1px solid #676758;
	    padding: 15px;
	    font-family: 'Roboto', sans-serif;
	    max-width: 100px;
	    margin: 0 auto;
	    cursor: pointer;
    }
    #passwordBtn:hover{
	    background: white;
    }
    
	#cardWrap{
		width: 80%;
		margin: 0 auto;
	}
    .mainImg{
	    width: 100%;
	    height: auto;
    }
  </style>
</head>


<body>

  	<div class="fullscreen">
  		<div class="card">
  			<div class="cardBorder">
			  	<p>Hello <span class='highlight'><?php echo $thisName; ?></span>,<br/>
			  	You've been invited to<br/>
			  	<span class='highlight'>VanCity Social</span><p>
			  	
			  	<p class="firstSocial">The first social network made <br/>
			  	just for Vancouver.</p>
			  	
			  	<p class='logo'><img src="/images/logo2.png"/></p>
			  </div>
		 </div>
		 
	  	<div class="cardSmall">
  			<div class="cardBorder">
			  	<p>Scroll Down to Learn More</p>
  			</div>
  		</div>
  		
  	</div>
  	

  	<div id="cardWrap">
  		<img src="../images/air_mockup.jpg" class="mainImg" />
  	</div>
  	
  	<div>
  		<div class="card">
  			<div class="cardBorder">
			  	<p class="extraTitle">Meet new people.</p>
			  	<p class='extra'>VanCity Social organizes posts by topics rather than friendships, so there's always a chance to interact with new people. Choose your favourite topics and connect with like-minded people throughout the city.</p>
			  	
			  	<p class="extraTitle">Stay up to date with local news and events.</p>
			  	<p class='extra'>The News Feed will show you a stream of the latest posts from Vancouver's most followed and trusted blogs.</p>
			  	
			  	<p class="extraTitle">Enjoy clutter-free, relevant content.</p>
			  	<p class='extra'>No game invites. No business pages. No apps. No spam. Because VanCity Social is an invite-only network, you can count on content staying on-topic.</p>
			  	<div class="spacer">&nbsp;</div>
			  	
			  	
  			</div>
  		</div>
  	</div>
  	
  	
  	<!-- note: these show fade in and slide up as the user scrolls down -->
  	
  	<div>
  		<div class="card">
  			<div class="cardBorder">
			  	<p class="extraTitle">Quick Sign Up</p>
				<p class="extra">Our Twitter invitation system pulls your Name and Image from the Twitter API, so all you have to do is choose a password.</p>
				
				<input type="text" id="password" value="Choose a Password" />
				<div id="passwordBtn">Sign Up</div>
				
			  	<div class="spacer">&nbsp;</div>
  			</div>
  		</div>
  	</div>
  	
  	
  	<div class="spacer">&nbsp;</div>
  	<div class="spacer">&nbsp;</div>
  	
</body>
</html>