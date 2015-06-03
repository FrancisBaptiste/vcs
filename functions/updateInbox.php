<?php
/*
$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
$db = mysql_selectdb("vancitys_vancitysocial");

$conID = $_POST['conversation'];
$id = $_POST['user'];

$messageUpdate = mysql_query("UPDATE messages SET message_read=1 WHERE conversation_id=$conID");
$conversationUpdate = mysql_query("UPDATE conversations SET inbox=0 WHERE id=$conID");


#check if there are any conversations with unread messages.
#if not set inbox in users to 0, else set to 1

$conQ = mysql_query("SELECT * FROM conversations WHERE person_a=$id OR person_b=$id");
$inboxBool = 0;

while($conRow = mysql_fetch_assoc($conQ)){
	$thisInbox = $conRow['inbox'];
	if($thisInbox == 1){
		$inboxBool = 1;
	}
}

mysql_query("UPDATE users SET inbox=$inboxBool WHERE id=$id");
*/


require("../includes/encryption.php");
require("../includes/setup.php");

$conID = $_POST['conversation'];
$id = $_POST['user'];
$id = decrypt_id($id);


#get the message user
#if the message was made by the same person reading it, don't change the status

$messageUpdate = $db->query("UPDATE messages SET message_read=1 WHERE conversation_id=$conID AND user_id!=$id");
if($db->affected_rows >= 1){
	$conversationUpdate = $db->query("UPDATE conversations SET inbox=0 WHERE id=$conID");
}


#check if there are any conversations with unread messages.
#if not set inbox in users to 0, else set to 1

#can't figure out why I added this bit of code down here
/*
$conQ = $db->query("SELECT * FROM conversations WHERE person_a=$id OR person_b=$id");
$inboxBool = 0;

while($conRow = $conQ->fetch_assoc() ){
	$thisInbox = $conRow['inbox'];
	if($thisInbox == 1){
		$inboxBool = 1;
	}
}
*/


#the new way
$conQ = $db->query("SELECT * FROM conversations WHERE person_a=$decodedID OR person_b=$decodedID");
$inboxBool = 0;

while($conRow = $conQ->fetch_assoc() ){
	$thisInbox = $conRow['inbox'];
	$thisConID = $conRow['id'];
	$messQ = $db->query("SELECT * FROM messages WHERE user_id!=$decodedID AND conversation_id=$thisConID AND message_read=0");
	while($messRow = $messQ->fetch_assoc() ){
		if($messRow['message_read'] == 0){
			$inboxBool = 1;
		}
	}
}

$db->query("UPDATE users SET inbox=$inboxBool WHERE id=$id");


?>