<?php

#set the messages to read
#set the conversation inbox flag to 0, meaning no unread messages

if( isset($_GET['conversation'])){
	$con = $_GET['conversation'];
	$messageUpdate = $db->query("UPDATE messages SET message_read=1 WHERE conversation_id=$con AND user_id!=$decodedID");
	if($db->affected_rows >= 1){
		$conversationUpdate = $db->query("UPDATE conversations SET inbox=0 WHERE id=$con");
	}

}else{
	$messageUpdate = $db->query("UPDATE messages SET message_read=1 WHERE conversation_id=$topConversation AND user_id!=$decodedID");
	if($db->affected_rows >= 1){
		$conversationUpdate = $db->query("UPDATE conversations SET inbox=0 WHERE id=$topConversation");
	}
}


#check if there are any conversations with unread messages.
#if not set inbox in users to 0, else set to 1

#I'm not sure why I added this bit of code. -Fran May 27/15
#this is the old way that's broken
/*
$conQ = $db->query("SELECT * FROM conversations WHERE person_a=$decodedID OR person_b=$decodedID");
$inboxBool = 0;

while($conRow = $conQ->fetch_assoc() ){
	$thisInbox = $conRow['inbox'];
	$thisConID = $conRow['id'];
	if($thisInbox == 1){
		$inboxBool = 1;
	}
}
*/

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

#I have to add to this
#search all messages in conversation for any row not created by myself with a read flag of 0
# $db->query("SELECT * FROM messages WHERE user_id!=$decodedID AND conversation_id=$thisConID AND message_read=0");
#if anything gets returned then set inbox to 1, else set it to 0

$db->query("UPDATE users SET inbox=$inboxBool WHERE id=$decodedID");

?>