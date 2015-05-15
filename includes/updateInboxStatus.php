<?php

#set the messages to read
#set the conversation inbox flag to 0, meaning no unread messages

if( isset($_GET['conversation'])){
	$con = $_GET['conversation'];
	$messageUpdate = $db->query("UPDATE messages SET message_read=1 WHERE conversation_id=$con");
	$conversationUpdate = $db->query("UPDATE conversations SET inbox=0 WHERE id=$con");
}else{
	$messageUpdate = $db->query("UPDATE messages SET message_read=1 WHERE conversation_id=$topConversation");
	$conversationUpdate = $db->query("UPDATE conversations SET inbox=0 WHERE id=$topConversation");
}


#check if there are any conversations with unread messages.
#if not set inbox in users to 0, else set to 1

$conQ = $db->query("SELECT * FROM conversations WHERE person_a=$decodedID OR person_b=$decodedID");
$inboxBool = 0;

while($conRow = $conQ->fetch_assoc() ){
	$thisInbox = $conRow['inbox'];
	if($thisInbox == 1){
		$inboxBool = 1;
	}
}

$db->query("UPDATE users SET inbox=$inboxBool WHERE id=$decodedID");

?>