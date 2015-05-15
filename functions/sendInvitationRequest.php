<?php

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$twitter = $_POST['twitter'];

$message = "New VanCity Social user request: \n";
$message .= "Name: $name \n";
$message .= "Email: $email \n";
$message .= "Password: $password \n";
$message .= "Twitter: $twitter \n";

$send = mail("fran.baptiste@gmail.com", "VanCity Social Request", $message);

if($send){
    echo "true";
}else{
    echo "false";
}

?>