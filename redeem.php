<?php

require("includes/setup.php");
require("includes/encryption.php");

if(isset($_COOKIE['user'])){
    $id = $_COOKIE['user'];
}else{
    $id = null;
}


if(isset($_POST['code'])){
	$code = $_POST['code'];
	$email1 = $_POST['email1'];
	$email2 = $_POST['email2'];
	$password = $_POST['password'];
	$username = $_POST['username'];

	if($email1 == $email2){
		$q = $db->query("SELECT * FROM invites WHERE invite='$code' AND used=0");
		if($q){
			$message = "Thank you!";
			$success = 1;
			$q = $db->query("UPDATE invites SET used=1 WHERE invite='$code'");

			$thisImage = "http://vancitysocial.ca/images/noProfile.jpg";
			$thisInbox = 1;
			$newUser = $db->prepare("INSERT INTO users(name,password,email,image,friendlist,inbox) values(?,?,?,?,?,?)");
			$newUser->bind_param("ssssi", $username, $password, $email1, $thisImage, $thisInbox, $thisInbox);
			$newUser->execute();

			$personB = $db->insert_id;

			$conInt = 1;
			$firstConversation = $db->prepare("INSERT INTO conversations (person_a, person_b, exchange_count, inbox) values(?, ?, ?, ?)");
			$firstConversation->bind_param('iiii', $conInt, $personB, $conInt, $conInt);
			$firstConversation->execute();

			$thisConversation = $db->insert_id;

			$firstMess = "Hi, welcome to VanCity Social. My name is Fran. I created this website. If you have any questions about it let me know. If you click on your name in the top right you can add some more info to your account, an image and an about blurb.";
			$welcomeMessage = $db->prepare("INSERT INTO messages (message, conversation_id, user_id) values(?, ?, ?)");
			$welcomeMessage->bind_param("sii", $firstMess, $thisConversation, $conInt);
			$welcomeMessage->execute();


			if($welcomeMessage){
				$encryptedID = encrypt_id($personB);
				$set = setcookie("user", $encryptedID, time()+3600*336, "/");
				header('Location: '.SITE_URL . 'app.php');
			}else{
				$message = "Something didn't work. The administrator has been notified.";
				$send = mail("fran.baptiste@gmail.com", "Failed Registrations on VCS", "$email1 tried to use code $code with password $password and username $username but it didn't work.");
			}
		}else{
			$message = "That Code isn't valid.";
		}
	}else{
		$message = "Emails don't match.";
	}
}

?>

<!DOCTYPE html>
    <head>
    	<title>Vancity Social</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link href='http://fonts.googleapis.com/css?family=Roboto:700,300,400' rel='stylesheet' type='text/css'>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/main.js"></script>
    </head>

    <body id="splash-body">
    	<?php include_once("analyticstracking.php") ?>

    	<div id="fullMask"></div>

        <div id="wrapper">

            <div id="mainHeader">
                <img src="images/logo2.png"/>
                <div id='loginButton'>Member log in</div>
            </div>

        </div> <!-- end of wrapper -->


        <div id="redeem">
	        <div id="redeemInner">
		        <?php
		        if(isset($message)){
				    echo "<h2>$message</h2>";
		        }else{
			        echo "<h2>Redeem Code Now</h2>";
		        }

			    if(!isset($success)){
		        ?>

		        <div id="redeemForm">
			        <form action="redeem.php" method="post">
				        <label for="code">Invitation Code</label><br/>
				        <input type="text" name="code" /><br/>
				        <label for="email1">Email Address</label><br/>
				        <input type="text" name="email1" /><br/>
				        <label for="email2">Confirm Email Address</label><br/>
				        <input type="text" name="email2" /><br/>
				        <label for="username">User Name</label><br/>
				        <input type="text" name="username" /><br/>
				        <label for="password">Create a Password</label><br/>
				        <input type="text" name="password" /><br/>
				        <input type="submit" value="Submit" id="submitCode" />
			        </form>
		        </div>
		        <?php
			        }
			    ?>
	        </div>
        </div>

        <div id="splash-features" style="padding: 50px;">
	        <h4>Meet like-minded Vancouverites. Read and comment on the latest news.</h4>
        </div>


        <div id="splash-footer">
			<div id="copyright">Copyright &copy;2015 VanCitySocial.ca</div>
	        <ul>
		        <li><a href="static.php?privacy">Privacy Notice</a></li>
		        <li><a href="static.php?terms">Terms of Use</a></li>
		        <li><a href="static.php?about">About Us</a></li>
		        <li>Contact Us: <a href="mailto:help@vancitysocial.ca">help@VanCitySocial.ca</a></li>
	        </ul>

        </div>

        <div id="fullScreen"></div>
        <?php include("includes/login-signup.php"); ?>

    </body>

</html>