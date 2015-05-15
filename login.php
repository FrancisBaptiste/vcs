<?php

require("includes/setup.php");

$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
$db = mysql_selectdb("vancitys_vancitysocial");

require("includes/encryption.php");

#what page is this coming from?
$page = "";
if(isset($_POST['page'])){
	$page = $_POST['page'];
}


if(isset($_POST['password']) && isset($_POST['email'])){
    $thisPass = $_POST['password'];
    $thisEmail = $_POST['email'];
    
    $q = mysql_query("SELECT * FROM users WHERE email='$thisEmail'");
    
    while($r = mysql_fetch_assoc($q)){
        $id = $r['id'];
    }
    
    if(mysql_numrows($q) == 0){
        echo "wrong email";
    }else{
        
        $q2 = mysql_query("SELECT * FROM users WHERE password='$thisPass'");
        
        if(mysql_numrows($q2) == 0){
            echo "Good email but wrong password";
        }else{
            
            #encrypt the id
            $encryptedID = encrypt_id($id);
            #$encryptedID = $id;
            
            $set = setcookie("user", $encryptedID, time()+3600*336, "/");
            
            
            if($page == "main"){
	            header('Location: '.SITE_URL.'app.php');
	            #echo " header didn't work";
            }else if ($page == "news"){
            	
            	$url = "Location: ".SITE_URL."news.php?story=" . $_POST['story'];
	            header($url);
            }
            
        }
        
    }
    
}

 
?>