<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");
require("../includes/encryption.php");
require("../includes/setup.php");

$id = $_POST['id'];

if($id == "" || $id == NULL){
	header("Location: " . SITE_URL);
	exit();
}

$id = decrypt_id($id);

$about = $_POST['about'];

$emailNotifications = $_POST['emailNotifications'];

$allowedExts = array("gif", "jpeg", "jpg", "png", "JPG", "PNG", "GIF", "JPEG");
$extension = end(explode(".", $_FILES["file"]["name"]));

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 1000000)
&& in_array($extension, $allowedExts)){
  if ($_FILES["file"]["error"] > 0){
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
  }else{
    #echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    #echo "Type: " . $_FILES["file"]["type"] . "<br>";
    #echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    #echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    if (file_exists("images/profilePics/$id.jpg")){
      echo $_FILES["file"]["name"] . " already exists. ";
      move_uploaded_file($_FILES["file"]["tmp_name"], "../images/profilePics/$id.jpg");
      
    }else{
     # move_uploaded_file($_FILES["file"]["tmp_name"], "images/profilePics/" . $_FILES["file"]["name"]);
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "../images/profilePics/$id.jpg");
    }
    
    $imageURL = "http://vancitysocial.ca/images/profilePics/$id.jpg";
    mysql_query("UPDATE users SET image='$imageURL' WHERE id='$id'");
    
    include('simpleImage.php');
	$thisImage = new SimpleImage(); 
	$thisImage->load("../images/profilePics/$id.jpg"); 
	$thisImage->resize(200, 200);
	$saveImage = $thisImage->save("../images/profilePics/$id.jpg");
  }
}

#$db->query("UPDATE users SET about='$about' WHERE id='$id'");

$aboutQ = $db->query("SELECT about FROM users where id=$id");
while($row = $aboutQ->fetch_assoc()){
	$oldAbout = $row['about'];
}

if($about != $oldAbout){
	$statement = $db->prepare("UPDATE users SET about=? WHERE id=$id");
	$statement->bind_param("s", $about);
	$statement->execute();
	
	$postText = $about;

	$statement = $db->prepare("INSERT INTO posts(uid, iid, text) values(?,42,?)");
	$statement->bind_param("is", $id, $postText);
	$statement->execute();
}



if($emailNotifications == "emailNotifications"){
	$db->query("UPDATE users SET email_notifications='1' WHERE id='$id'");
}else{
	$db->query("UPDATE users SET email_notifications='0' WHERE id='$id'");
}


header('Location: '.SITE_URL . 'app.php');

 
?>