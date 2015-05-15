<?php

#what page is this coming from?
$page = "";
if(isset($_GET['page'])){
	$page = $_GET['page'];
}


#signup process
if(isset($_POST['email'])||isset($_POST['password'])||isset($_POST['name'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    
    #check if email exists first. Only one account per email address.
    $check = $db->query("SELECT * FROM users WHERE email='$email'");
    $num = $check->num_rows;
    
    if($num > 0){
        $newUser = "already exists";
    }else{
        $q = $db->query("INSERT INTO users(name, password, email) values('$name', '$password', '$email')");
        if($q){
            $newUser = "welcome";
            $id = $q->insert_id;
            $set = setcookie("user", $id, time()+3600*336, "/");
            
            if($set){
	            if($page == "main"){
		            header('Location: http://vancitysocial.ca/app.php');
	            }else if ($page == "news"){
		            header('Location: http://vancitysocial.ca/news.php');
	            }
            }
            
        }else{
            $newUser = "fail";
        }
    }
}
?>