<?php
/*
$key = array('p', 'j', 'g', 'z', 'm', '8', 'c', 'h', '5', 'f', 'u', 'b', '6', 'r', 'w', '9', '2', '7', 'i', '1', 'y', 's', '4', 'n', 'o', '0', 'q', 'd', 'l', 'v', 'a', 't', 'e', 'x', 'k', '3','!');
$position = 0;
$counter = 0;
$encodedLength = 30;

if( isset($_GET['encode'])){
	
	$id = $_GET['encode'];
	$idCharacters = str_split($id);
	$encodedLength = $encodedLength + count($idCharacters);
	
	$encoded = "";
	while($counter < $encodedLength){
		foreach($idCharacters as $digit){
			$encoded .= $key[$digit];
			$counter++;
			for($i = 0; $i < $digit; $i++){
				$first = array_shift($key); array_push($key, $first);
			}
		}
		
	}
	
	echo $encoded;
	
}else if( isset($_GET['decode'])  ){
	
	$encoded = $_GET['decode'];
	
	$values = str_split($encoded);
	
	$decoded = "";
	
	foreach($values as $v){
		$digit = array_search($v, $key);
		$decoded .= $digit;
		for($i = 0; $i < $digit; $i++){
			$first = array_shift($key); array_push($key, $first);
		}
	}
	
	$encodedLength = count($values);
	
	#the length of the string will tell us how many recursions to look for
	#if the recursions aren't consistent then it's not a proper code
	
	#echo "<br/>length = $encodedLength<br/>";
	
	switch($encodedLength){
		case 31:
			if(substr_count($decoded, substr($decoded, 0, 1)) == 31){
				$decoded = substr($decoded, 0, 1);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 32:
			if(substr_count($decoded, substr($decoded, 0, 2)) == 16){
				$decoded = substr($decoded, 0, 2);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 33:
			if(substr_count($decoded, substr($decoded, 0, 3)) == 11){
				$decoded = substr($decoded, 0, 3);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 36:
			#36 test for codes 4 digits long and 6 digits long
			if(substr_count($decoded, substr($decoded, 0, 4)) == 9){
				$decoded = substr($decoded, 0, 4);
			}else if(substr_count($decoded, substr($decoded, 0, 6)) == 6){
				$decoded = substr($decoded, 0, 6);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 35:
			if(substr_count($decoded, substr($decoded, 0, 5)) == 7){
				$decoded = substr($decoded, 0, 5);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
	}
	
	echo $decoded;
	
}
*/


function encrypt_id($thisID){
	$key = array('p', 'j', 'g', 'z', 'm', '8', 'c', 'h', '5', 'f', 'u', 'b', '6', 'r', 'w', '9', '2', '7', 'i', '1', 'y', 's', '4', 'n', 'o', '0', 'q', 'd', 'l', 'v', 'a', 't', 'e', 'x', 'k', '3','!');
	$position = 0;
	$counter = 0;
	$encodedLength = 30;
		
	$id = $thisID;
	$idCharacters = str_split($id);
	$encodedLength = $encodedLength + count($idCharacters);
	
	$encoded = "";
	while($counter < $encodedLength){
		foreach($idCharacters as $digit){
			$encoded .= $key[$digit];
			$counter++;
			for($i = 0; $i < $digit; $i++){
				$first = array_shift($key); array_push($key, $first);
			}
		}
	}
	return $encoded;
}


function decrypt_id($thisID){
	$key = array('p', 'j', 'g', 'z', 'm', '8', 'c', 'h', '5', 'f', 'u', 'b', '6', 'r', 'w', '9', '2', '7', 'i', '1', 'y', 's', '4', 'n', 'o', '0', 'q', 'd', 'l', 'v', 'a', 't', 'e', 'x', 'k', '3','!');
	$position = 0;
	$counter = 0;
	$encodedLength = 30;
	
	$encoded = $thisID;
	
	$values = str_split($encoded);
	
	$decoded = "";
	
	foreach($values as $v){
		$digit = array_search($v, $key);
		$decoded .= $digit;
		for($i = 0; $i < $digit; $i++){
			$first = array_shift($key); array_push($key, $first);
		}
	}
	
	$encodedLength = count($values);
	
	switch($encodedLength){
		case 31:
			if(substr_count($decoded, substr($decoded, 0, 1)) == 31){
				$decoded = substr($decoded, 0, 1);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 32:
			if(substr_count($decoded, substr($decoded, 0, 2)) == 16){
				$decoded = substr($decoded, 0, 2);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 33:
			if(substr_count($decoded, substr($decoded, 0, 3)) == 11){
				$decoded = substr($decoded, 0, 3);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 36:
			#36 test for codes 4 digits long and 6 digits long
			if(substr_count($decoded, substr($decoded, 0, 4)) == 9){
				$decoded = substr($decoded, 0, 4);
			}else if(substr_count($decoded, substr($decoded, 0, 6)) == 6){
				$decoded = substr($decoded, 0, 6);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		case 35:
			if(substr_count($decoded, substr($decoded, 0, 5)) == 7){
				$decoded = substr($decoded, 0, 5);
			}else{
				$decoded = "NOT A CODE";
			}
			break;
		default:
			$decoded = "NOT A CODE";
	}
	
	return $decoded;
		
}


?>