<?php
require("../includes/encryption.php");
require("../includes/setup.php");

$id = $_POST['id'];

if($id == "" || $id == NULL){
	header("Location: " . SITE_URL);
	exit();
}

$id = decrypt_id($id);

$about = $_POST['about'];
$name = $_POST['username'];

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

    if (file_exists("images/profilePics/$id.jpg")){
      echo $_FILES["file"]["name"] . " already exists. ";
      move_uploaded_file($_FILES["file"]["tmp_name"], "../images/profilePics/$id.jpg");

    }else{
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


$aboutQ = $db->query("SELECT * FROM users where id=$id");
while($row = $aboutQ->fetch_assoc()){
	$oldAbout = $row['about'];
	$oldName = $row['name'];
}

if($about != $oldAbout){
	$statement = $db->prepare("UPDATE users SET about=? WHERE id=$id");
	$statement->bind_param("s", $about);
	$statement->execute();
	$postText = $about;
}

if($name != $oldName){
	$Nstatement = $db->prepare("UPDATE users SET name=? WHERE id=$id");
	$Nstatement->bind_param("s", $name);
	$Nstatement->execute();
}


if($emailNotifications == "emailNotifications"){
	$db->query("UPDATE users SET email_notifications='1' WHERE id='$id'");
}else{
	$db->query("UPDATE users SET email_notifications='0' WHERE id='$id'");
}

header('Location: '.SITE_URL . 'app.php');


?>