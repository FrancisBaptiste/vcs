<?php

require("includes/setup.php");

#setcookie("user", "", time() - 3600);

#$id = $_COOKIE['user'];

#$logout = setcookie("user", $id, 1);

#$logout = setcookie("user", "X");

#setcookie("user", "");
setcookie("user", "", time()-1, "/");
header("Location: ".SITE_URL."app.php");

#echo $_COOKIE['user'];

// unset cookies
/*
if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
}
*/

/*
if($logout){
    echo "TRUE ";
    echo $_COOKIE['user'];
}else{
    echo "FALSE";
}
*/
//header("Location: http://pixelborne.com/vancitysocial");

?>