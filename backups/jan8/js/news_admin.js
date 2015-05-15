$(function(){
	$("button").click(function(){
		var $title = $(this).parent().find(".title").text();
		var $source = $(this).parent().find(".source").text();
		var $excerpt = $(this).parent().find(".excerpt").val();
		var $link = $(this).parent().find(".link").attr("href");
		var $image = $(this).parent().find(".image").val();
		var $thisBlock = $(this).parent();
		
		var $photoCredit = $(this).parent().find(".photoCredit").val();
		var $writerCredit = $(this).parent().find(".writerCredit").val();
		
		if ( $photoCredit == "" ) {
			$photoCredit = $source;
		}
		if ( $writerCredit == "" ) {
			$writerCredit = $source;
		}
		
		$.post("functions/approveNews.php", {title:$title, source:$source, excerpt:$excerpt, link:$link, image:$image, photographer:$photoCredit, writer:$writerCredit}, function(data){
			if(data == "TRUE"){
				
				$thisBlock.remove();
				
			}else{
				alert("something didn't work");
			}
		});
	});
});