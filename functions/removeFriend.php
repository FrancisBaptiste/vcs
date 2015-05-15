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


if(strpos($friendlist, ",") !== false){
	$friendArray = explode(",", $friendlist);
	if (($key = array_search($friendID, $friendArray)) !== false) {
	    unset($friendArray[$key]);
	}
	$newlist = implode(",", $friendArray);
}else{
	$newlist = "";
}

$q = $db->query("UPDATE users SET friendlist='$newlist' WHERE id=$user");
if($q){
    echo "true";
}else{
    echo "false";
}


?>