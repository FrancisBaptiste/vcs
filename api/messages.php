<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

$limit = 10;

$conID = $_GET['id'];

#if get var 'all' is set, get rid of the limit

$conversationMessages = array();
$messagesQ = $db->query("SELECT * FROM messages WHERE conversation_id=$conID");
while($messRow = $messagesQ->fetch_assoc() ){
	$messageID = $messRow['id'];
	$message = stripslashes($messRow['message']);
	$messageDate = $messRow['date'];
	$messageRead = $messRow['message_read'];
	$messageUser = $messRow['user_id'];
	
	
	$partnerQ = $db->query("SELECT * FROM users WHERE id='$messageUser'");
	while($partnerInfo = $partnerQ->fetch_assoc() ){
		$partnerName = $partnerInfo['name'];
		$partnerAbout = $partnerInfo['about'];
		$partnerPic = $partnerInfo['image'];
		if( $partnerPic == "" ){
			$partnerPic = 'http://vancitysocial.ca/images/noProfile.jpg';
		}
		$thisPartner = array(
			"id" => $partnerID,
			"name" => $partnerName,
			"image" => $partnerPic,
		);
	}
	
	
	$message = array(
		"id" => $messageID,
		"user" => $thisPartner,
		"date" => $messageDate,
		"text" => $message,
		"read" => $messageRead
	);
	array_push($conversationMessages, $message);
}
	
	

header('Content-Type: application/json');
echo json_encode($conversationMessages);


?>