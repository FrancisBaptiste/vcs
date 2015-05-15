<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

require("../includes/encryption.php");
require("../includes/setup.php");

$storyID = $_POST['storyID'];
$comment = $_POST['comment'];

$userID = $_POST['userID'];
$userID = decrypt_id($userID);

$guestName = $_POST['guestName'];
$response = $_POST['responseID'];
$thread = $_POST['thread'];


$q = $db->prepare("INSERT INTO news_comments(story_id, text, user_id, guest_name, response_id, thread_count) values(?, ?, ?, ?, ?, ?)");
$q->bind_param('isisii', $storyID, $comment, $userID, $guestName, $response, $thread);
$q->execute();

$insertID = $q->insert_id;


$iid = 41;
$statement = $db->prepare("INSERT INTO posts(uid, iid, text, news_id) values(?,?,?,?)");
$statement->bind_param("iisi", $userID, $iid, $comment, $storyID); #used to be insertID
$statement->execute();


if($response != 0){
    $getUID = $db->query("SELECT user_id FROM news_comments WHERE id=$response");
    while($uid = $getUID->fetch_assoc() ){
        $originalUser = $uid['user_id'];
    }
    
    if($originalUser != 0){
	    //create the notification
		$n = $db->query("INSERT INTO news_notifications(user_id, commenter_id, story_id) values($originalUser, $userID, $storyID)");
    }
    
}


if($q){
    #echo "true";
    echo $insertID;
}else{
    echo "false";
}


?>