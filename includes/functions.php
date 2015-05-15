<?php
	
function makeClickableLinks($text){
	$newString = "";
    $words = explode(" ", $text);
    foreach($words as $word){
        if(strtolower(substr($word, 0, 4)) == "http" || strtolower(substr($word, 0, 3)) == "www"){
            if(strlen($word) >= 20){
                $newString .= "<a href='$word' target='blank' alt>$word</a> ";
            }else{
                $newString .= "<a href='$word' target='blank' alt>[Link]</a> ";
            }
        }else{
			$newString .= $word . " ";
		}
    }
	$newString = substr($newString, 0, -1);
	return $newString;
}
	
	
	
	
	
function colorTag($tag){
	switch($tag){
		case 0:
			return "rgb(112, 112, 158)"; #general
			break;
		case 1:
			return "rgb(68,68,220)"; #happening this weekend
			break;
		case 2:
			return "rgb(130,130,225)"; #open question
			break;
		case 3:
			return "rgb(135,25,190)"; #local news
			break;
		case 4:
			return "rgb(107, 174, 108)"; #restaurants
			break;
		case 5:
			return "rgb(195, 103, 168)"; #live music
			break;
		case 6:
			return "rgb(120,160,210)"; #things to do
			break;
		case 7:
			return "rgb(40,130,175)"; #vancouver canucks
			break;
		case 8:
			return "rgb(71,185,195)"; #biking
			break;
		case 9:
			return "rgb(10,155,170)"; #television
			break;
		case 10:
			return "rgb(83, 205, 115)"; #shopping
			break;
		case 11:
			return "rgb(20,175,100)"; #beer
			break;
		case 12:
			return "rgb(182,90,90)"; #wine
			break;
		case 13:
			return "rgb(155,115,115)"; #arts (currently set to biking)
			break;
		case 14:
			return "rgb(189, 142, 199)"; #books
			break;
		case 15:
			return "rgb(150, 142, 199)"; #snowboarding
			break;
		case 16:
			return "rgb(142, 175, 199)"; #food
			break;
		case 17:
			return "rgb(142, 199, 177)"; #open mics
			break;
		case 18:
			return "rgb(142, 199, 147)"; #music
			break;
		case 19:
			return "rgb(144, 119, 137)"; #hockey
			break;
		case 20:
			return "rgb(129, 119, 144)"; #yoga
			break;
		case 21:
			return "rgb(119, 144, 131)"; #fitness
			break;
			
		case 22:
			return "rgb(145,145,225)"; #politics
			break;
			
		case 23:
			return "rgb(117,49,225)"; #fashion
			break;
		
		default:
			return "rgb(55, 55, 155)";
			break;
		
	}
	
}
	
?>