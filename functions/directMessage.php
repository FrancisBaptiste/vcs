<?php

require("../includes/encryption.php");
require("../includes/setup.php");

#check if conversation already exists between two users

$passiveUser = $_POST['passive'];

$activeUser = $_POST['active'];
$activeUser = decrypt_id($activeUser);

$message = $_POST['message'];

$check1 = $db->query("SELECT * FROM conversations WHERE person_a='$passiveUser' AND person_b='$activeUser' OR person_a='$activeUser' AND person_b='$passiveUser'");

while( $row = $check1->fetch_assoc() ){
	$conID = $row['id'];
}

if( isset($conID) && $conID != 0 ){

	#insert message into messages using $conID
	$statement = $db->prepare("INSERT INTO messages (message, conversation_id, user_id) values(?, ?, ?)");
	$statement->bind_param('sii', $message, $conID, $activeUser);
	$statement->execute();

	if($statement){
		echo "true";
		$inbox = $db->query("UPDATE conversations SET inbox=1 WHERE id=$conID");
		$userInbox = $db->query("UPDATE users SET inbox=1 WHERE id=$passiveUser");
	}

}else{

	#create a new conversation
	#then insert message into messages using the newly created #conID

	#$thisInsert = $db->query("INSERT INTO conversations ('person_a', 'person_b', 'exchange_count', 'inbox') values('$activeUser', '$passiveUser', '1', '1')");

	$ex = 1;
	$in = 1;

	$thisInsert = $db->prepare("INSERT INTO conversations (person_a, person_b, exchange_count, inbox) values(?, ?, ?, ?)");
	$thisInsert->bind_param('iiii', $activeUser, $passiveUser, $ex, $in);
	$thisInsert->execute();

	if($thisInsert){
		$newConID = $thisInsert->insert_id;

		$statement = $db->prepare("INSERT INTO messages (message, conversation_id, user_id) values(?, ?, ?)");
		$statement->bind_param('sii', $message, $newConID, $activeUser);
		$statement->execute();

		if($statement){
			echo "true";
			$userInbox = $db->query("UPDATE users SET inbox=1 WHERE id=$passiveUser");
		}
	}else{
		echo "insert didn't work";
	}


}

/*
$statement = $db->prepare("INSERT INTO messages(message, conversation_id, user_id) values(?, ?, ?)");
$statement->bind_param('sii', $message, $conversation, $user);
$statement->execute();
*/


?>