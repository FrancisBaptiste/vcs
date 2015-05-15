<?php

require("../includes/setup.php");

$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];

#check if email exists first. Only one account per email address.

#$check = mysql_query("SELECT * FROM users WHERE email='$email'");
#$num = mysql_num_rows($check);

$result = $db->query("SELECT * FROM users WHERE email='$email'");
$num = $result->num_rows;

if($num > 0){
    echo "Already Exists";
}else{
    #$q = mysql_query("INSERT INTO users(name, password, email) values('$name', '$password', '$email')");
    
    #$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");
    
    $statement = $db->prepare("INSERT INTO users(name, password, email) values(?, ?, ?)");
    $statement->bind_param('sss', $name, $password, $email);
    $statement->execute();
    
    if($statment){
        echo "TRUE";
    }else{
        echo "FALSE";
    }
}


?>