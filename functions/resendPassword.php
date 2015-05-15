<?php

require("../includes/encryption.php");
require("../includes/setup.php");


$email = $_POST['emailPassword'];

$q = $db->query("SELECT password FROM users WHERE email='$email'");


while($row = $q->fetch_assoc()){
	$thisPassword = $row['password'];
}



if($q){
	$send = mail($email, "VanCity Social Password Recovery", "Your password is... $thisPassword", "From: no-reply@vancitysocial.ca");
}


header("Location: " . SITE_URL);


?>