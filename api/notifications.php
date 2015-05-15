<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");


$id=$_GET['id'];

$q = $db->query("SELECT * FROM notifications WHERE uid=$id ORDER BY id DESC");

$allNotifications = array();

while($row = $q->fetch_assoc() ){
    $id = $row['id'];
    $commenter_id = $row['cid'];
    
    $cInfo = $db->query("SELECT * FROM users WHERE id=$commenter_id");
    while($info = $cInfo->fetch_assoc() ){
        $commenter_name = $info['name'];
        $commenter_image = $info['image'];
    }
    
    $pid = $row['pid'];
    $read = $row['notification_read'];
    
    $notification = array(
        "id" => $id,
        "commenter_id" => $commenter_id,
        "commenter_name" => $commenter_name,
        "commenter_image" => $commenter_image,
        "post_id" => $pid,
        "notification_read" => $read
    );
    
    array_push($allNotifications, $notification);
    
}

header('Content-Type: application/json');
echo json_encode($allNotifications);


?>