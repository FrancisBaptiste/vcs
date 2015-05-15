<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");


#what page is this coming from?
$page = "";
if(isset($_POST['page'])){
	$page = $_POST['page'];
}


#signup process
if(isset($_POST['email'])||isset($_POST['password'])||isset($_POST['name'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    
    #echo "$email $password $name ";
    
    #check if email exists first. Only one account per email address.
    $check = $db->query("SELECT * FROM users WHERE email='$email'");
    $num = $check->num_rows;
    
    if($num > 0){
        $newUser = "already exists";
        #echo " $newUser ";
    }else{
        #$q = $db->query("INSERT INTO users(name, password, email) values('$name', '$password', '$email')");
        $thisInsert = $db->prepare("INSERT INTO users (name, password, email) values(?, ?, ?)");
		$thisInsert->bind_param('sss', $name, $password, $email);
		$thisInsert->execute();
        
        if($thisInsert){
            $newUser = "welcome";
            #$id = mysql_insert_id();
            $id = $thisInsert->insert_id;
            $set = setcookie("user", $id, time()+3600*336, "/");
            
            #echo $id;
            
            if($set){
	            if($page == "main"){
		            header('Location: http://vancitysocial.ca/app.php');
		            #echo " header didn't work";
	            }else if ($page == "news"){
		            $url = "Location: http://vancitysocial.ca/news.php?story=" . $_POST['story'];
					header($url);
	            }
            }else{
	            #echo " set didn't fucking work!! ";
            }
            
        }else{
            $newUser = "fail";
        }
    }
}
?>