<?php
require("../includes/encryption.php");
require("../includes/setup.php");

$user = $_POST['user'];
$user = decrypt_id($user);
$friendID = $_POST['friend'];

$get = $db->query("SELECT friendlist FROM users WHERE id=$user");
while($row = $get->fetch_assoc()){
	$friendlist = $row['friendlist'];
}

if($friendlist != ""){
	$friendArray = explode(",", $friendlist);
	
	if(!in_array($friendID, $friendArray)){
		$newlist = $friendlist . "," . $friendID;
		$q = $db->query("UPDATE users SET friendlist='$newlist' WHERE id=$user");
	
		if($q){
		    echo "true";
		}else{
		    echo "false";
		}
	}else{
		echo "You already added this person.";
	}
}else{
	$q = $db->query("UPDATE users SET friendlist='$friendID' WHERE id=$user");
	
		if($q){
		    echo "true";
		}else{
		    echo "false";
		}
}





?>