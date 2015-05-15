<?php

if( $_GET['password'] != "golightlyDuck2424"){
	exit();
}

$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
$db = mysql_selectdb("vancitys_vancitysocial");

$sources = array(
  "http://rss.canada.com/get/?F259",
  "http://metronews.ca/news/vancouver/feed/",
  "http://vancouver.24hrs.ca/rss.xml",
  "http://www.straight.com/content/rss",
  "http://www.vancitybuzz.com/feed/",
  "http://604now.com/feed/",
  "http://www.miss604.com/feed",
  "http://feeds.feedburner.com/vancouverisawesome/oYhG",
  "http://www.insidevancouver.ca/feed/",
  "http://viewmagazine.ca/feed/",
  "http://maryinvancity.com/",
  "http://www.pangcouver.com/",
  "http://www.hellovancity.com/feed",
  "http://vancouverfoodster.com/feed/",
  
);

$sources2 = array(
	"http://www.vancouverslop.com/feeds/posts/default"
);

$allPosts = array();

foreach($sources as $source){
    $xml = simplexml_load_file($source);
    $site = $xml->channel->title;
    foreach($xml->channel->item as $post){
        
        $thisPost = array(
            "title" => $post->title,
            "date" => $post->pubDate,
            "timestamp" => strtotime($post->pubDate),
            "link" => $post->link,
            "source" => $site,
            "excerpt" => $post->description
        );
        
        #only add to array if the post is from the last 24 hours
        $sincePost = date('U') - strtotime($post->pubDate);
        if( $sincePost < (86400*2)){
	    #make sure this hasn't been posted about before
	    $thisTitle = $post->title;
	    $titleQ = mysql_query("SELECT * FROM news WHERE title='$thisTitle'");
	    $rowCount = mysql_num_rows($titleQ);
	    if( mysql_num_rows($titleQ) == 0){
		array_push($allPosts, $thisPost);
	    }
        }
        
    }
}

$reorder = $allPosts;

/*
$reorder = SortByKeyValue($allPosts, "timestamp");

function SortByKeyValue($data, $sortKey){
	$sort_flags="SORT_DESC";
    if (empty($data) or empty($sortKey)) return $data;
    $ordered = array();
    foreach ($data as $key => $value)
        $ordered[$value[$sortKey]] = $value;
    ksort($ordered, $sort_flags);
    return array_values($ordered);
}
*/

?>


<!DOCTYPE html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/news_admin.js"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="robots" content="noindex,nofollow">
    </head>     
    
    <body>
    	
    	<?php
    	
    	echo "<div>" . count($allPosts) . " Posts Listed</div>";
    	
    	
    	foreach($reorder as $story){
			$source = "";
			if($story['source'] == "Metro News » Vancouver"){
			$source = "Metro News Vancouver";
			}else if($story['source'] =="Vancouver Sun - News / Vancouver"){
			$source = "Vancouver Sun";
			}else if($story['source'] =="Vancity Buzz | Vancouver Events, News, Food, Lifestyle and More"){
			$source = "Vancity Buzz";
			}else if($story['source'] =="Home Stories"){
			$source = "24 Hour News Vancouver";
			}else if($story['source'] == "Vancouver Blog Miss604"){
			$source = "Miss604";
			}else if($story['source'] == "Inside Vancouver Blog"){
			$source = "Inside Vancouver";
			}else{
			$source = $story['source'];
	    }
	    
	    
	    echo "<div style='padding: 20px; float:left; width: 250px; height: 550px;'>";
	    echo "<h4 class='title'>" . $story['title'] . "</h4>";
	    echo "<span class='date'>" .$story['date'] . "</span><br/>";
	    echo "<span class='source'>" .$source . "</span><br/>";
	    echo "<textarea class='excerpt' rows='15' style='width:240px;'>" . htmlentities($story['excerpt']) . "</textarea><br/>";
	    echo "<a class='link' href='" . $story['link'] . "' target='blank'/>link</a><br/>";
	    echo "Image:<br/><input type='text' class='image'/><br/>";
	    echo "Photo Credit:<br/><input type='text' class='photoCredit'/><br/>";
	    echo "Writer Credit:<br/<br/><input type='text' class='writerCredit'/><br/>";
	    echo "<button>Approve</button>";
	    echo "</div>";
	}
    	?>
    	
    
    	
    </body>
    
</html>

