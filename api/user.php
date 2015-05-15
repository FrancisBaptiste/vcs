<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

$id=$_GET['id'];

$q = $db->query("SELECT * FROM users WHERE id=$id");

$allInterests = array();
$allLinks = array();

while($row = $q->fetch_assoc() ){
    $uid = $row['id'];
    $name = $row['name'];
    
    $image = $row['image'];
    /*
    $imageURL = "http://pixelborne.com/vancitysocial/images/profilePics/" . $uid . ".jpg";
    if (getimagesize($imageURL) !== false) {
        $image = $imageURL;
    }else{
        $image = "http://pixelborne.com/vancitysocial/images/noProfile.jpg";
    }
    */
    
    $about = $row['about'];
    $inbox = $row['inbox'];
    $friends = $row['friendlist'];
    $notifications = $row['email_notifications'];
    
    //getting all user interests
    
    $i = $db->query("SELECT iid FROM user_interests WHERE uid=$uid");
    
    while($iRow = $i->fetch_assoc() ){
        $iid = $iRow['iid'];
        $ii = $db->query("SELECT interest FROM interests WHERE id=$iid");
        //$interest = mysql_result($ii, 0);
        while($iiRow = $ii->fetch_assoc()){
	        $interest = $iiRow['interest'];
        }
        $thisInterest = array(
            "id" => $iid,
            "interest" => $interest
        );
        array_push($allInterests, $thisInterest);
    }
    
    //getting all user links
    /*
    $l = $db->query("SELECT link FROM user_links WHERE uid=$uid");
    
    while($lRow = $l->fetch_assoc() ){
        $link = $lRow['link'];
        array_push($allLinks, $link);
    }
    */
    
    $user = array(
        "id" => $uid,
        "name" => $name,
        "image" => $image,
        "about" => $about,
        "inbox" => $inbox,
        "friendlist" => $friends,
        "email_notifications" => $notifications
    );
    
    $user['interests'] = $allInterests;
    $user['links'] = $allLinks;
    
}

header('Content-Type: application/json');
echo json_encode($user);

?>