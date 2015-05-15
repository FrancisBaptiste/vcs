<?php

require("../twitteroauth/OAuth.php");
require("../twitteroauth/twitteroauth.php");

require("../includes/encryption.php");

function getConnectionWithAccessToken($oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth("1UbBvXIXAkd1SBQyQwKXnRICM", "HD3akxhs6kx2wznhYW1O4FVLbsMuvXDQVAlbCCAMDtg4XKXLNV", $oauth_token, $oauth_token_secret);
  return $connection;
}

//$newUsers = 'ChristineMcAvoy,DennisPang,michaelkwan';
/*
$newUsers ='Activ8Inc,HootVicky,erin_braincandy,johnleewriter,winsontang,janisbehan,bluelimemedia,deedls,chengsophia,CatherineOmega,';
$newUsers .= 'rebeccacoleman,reporton,MackFlavelle,nicolb,LiamLahey,missmandywong,Phanyxx,Kardboard,bsainsbury,Fairlite,';
$newUsers .= 'vanfoodster,deefontein,pawspix,Miss604,loveyourcake,cadijordan,NargesNirumvala,colene,alanranta,Hermida,';
$newUsers .= 'V5Nirv,Vancouverscape,bob_woolsey,TheAnndddyyyy,traveldiaries,estherbatycki,carlz8,JohnBiehler,RubieDanger,taylrn,';
$newUsers .= 'AY604,noraahern,wonderspark,laurenbacon,AmandaSmithDG,adrianmacnair,bencappellacci,arieanna,wwong17,srobarts,';
*/
$newUsers .= 'marcusli324,JorinCowley,';


$connection = getConnectionWithAccessToken("223750276-nQW959Ad370ywaYYitpSWELAUichO2rewCUcOv6q", "AG4JWdnItA1l3J8cio63lx7KXLaKsOXuZrlVPx2S33wPx");
$list = $connection->get('users/lookup', array('screen_name' => $newUsers));


$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");

$currentIndex = 40;

/*

foreach($list as $user){
	
	echo "<img src='" . $user->profile_image_url . "'/>";
		
	//Get the file
	$content = file_get_contents($user->profile_image_url);
	//Store in the filesystem.
	$fp = fopen('../images/profilePics/' . $currentIndex . '.jpg', "w");
	fwrite($fp, $content);
	fclose($fp);
	
	$name = $user->name;
	
	$imageLocation = "http://vancitysocial.ca/images/profilePics/$currentIndex.jpg";
	
	$password = encrypt_id($currentIndex);
	
	$statement = $db->query("INSERT INTO users(name,password,email,image) values('$name','$password','na','$imageLocation')");
	
	$currentIndex++;
}

*/

?>