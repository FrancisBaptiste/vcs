<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

$limit = 4;

$user = $_GET['user'];

$conversations = $db->query("SELECT * FROM conversations WHERE person_a='$user' OR person_b='$user' ORDER BY exchange_count DESC");


$allConversations = array();

while($con = $conversations->fetch_assoc() ){
	$conID = $con['id'];
	if( $con['person_a'] == $user ){
		$partnerID = $con['person_b'];
	}else{
		$partnerID = $con['person_a'];
	}
	
	$partnerQ = $db->query("SELECT * FROM users WHERE id='$partnerID'");
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
			"about" => $partnerAbout
		);
	}
		
	$thisCon = array(
		"id" => $conID,
		"exchange_count" => $con['exchange_count'],
		"inbox" => $con['inbox'],
		"partner" => $thisPartner,
	);
	
	array_push($allConversations, $thisCon);
}

header('Content-Type: application/json');
echo json_encode($allConversations);


?>