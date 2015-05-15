<?php

require("../includes/encryption.php");
require("../includes/setup.php");

$uid = $_POST['user'];
$uid = decrypt_id($uid);

$pid = $_POST['pid'];
$post = $_POST['post'];
$postUserId = $_POST['uid'];


$statement = $db->prepare("INSERT INTO comments(pid, uid, text) values(?,?,?)");
$statement->bind_param("iis", $pid, $uid, $post);
$statement->execute();


#$q = mysql_query("INSERT INTO comments(pid, uid, text) values($pid, $uid, '$post')");

if($statement){
    echo "true";
}else{
    echo "false";
}

//create the notification
$cid = $uid;

#$db->query("INSERT INTO notifications(uid, cid, pid) values($postUserId, $cid, $pid)");
$statement = $db->prepare("INSERT INTO notifications(uid, cid, pid) values(?,?,?)");
$statement->bind_param("iii", $postUserId, $cid, $pid);
$statement->execute();

//this part should work in theory, but I haven't tested it
//it's supposed to send a notification to everyone who commented on the post
//so whenever someone adds a comment, all other commenters are notified, much like how fb does it

$getAllCommenters = $db->query("SELECT DISTINCT uid FROM comments WHERE pid=$pid");

if($getAllCommenters){
	while($allC = $getAllCommenters->fetch_assoc()){
	    $thisUser = $allC['uid'];
	    if($thisUser != $uid){
	        #$db->query("INSERT INTO notifications(uid, cid, pid) values($thisUser, $cid, $pid)");
	        $statement = $db->prepare("INSERT INTO notifications(uid, cid, pid) values(?,?,?)");
	        $statement->bind_param("iii", $thisUser, $cid, $pid);
	        $statement->execute();
	    }
	}
}


?>