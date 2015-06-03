<script type="text/javascript">
	$(function(){
		$("#fullScreen").delay(100).fadeIn(function(){
			$("#welcome").slideDown();
		});

		$(".nextWelcome").click(function(){
			$("#sliderInner").animate({"margin-left": "-=400px"});
		});

		$(".nextFinished").click(function(){
			$("#welcome").slideUp(function(){
				$("#fullScreen").fadeOut();
			});
		});
	});
</script>

<div id="welcome">
	<div id="slider">
		<div id="sliderInner">
			<div class="slide">
				<h4>Welcome to Vancity Social</h4>
				<img src="images/screen1.jpg" alt="screenshot of vancity social" />

				<div id="welcomeParagraphs">
					<p>This is  quick guide on how to use VanCity Social. If you ever have any questions beyond this tour, just contact the site administrator via your inbox.</p>
				</div>

				<div class="nextWelcome">Next</div>
			</div>
			<div class="slide">
				<h4>The Timeline</h4>
				<img src="images/screen2.jpg" alt="screenshot of vancity social" />
				<div id="welcomeParagraphs">
					<p>The middle column is for posts. You can click on a user's name if you'd like to friend them or send them a direct message. Also inbox notifications come here.</p>
				</div>
				<div class="nextWelcome">Next</div>
			</div>
			<div class="slide">
				<h4>The News Feed</h4>
				<img src="images/screen3.jpg" alt="screenshot of vancity social" />
				<div id="welcomeParagraphs">
					<p>The right column is the news feed. Click and comment on the latest local news and events. Your comments will appear in the middle column.</p>
				</div>
				<div class="nextWelcome">Next</div>
			</div>
			<div class="slide">
				<h4>Topics</h4>
				<img src="images/screen4.jpg" alt="screenshot of vancity social" />
				<div id="welcomeParagraphs">
					<p>On the right are topics. All posts can be filtered by topics. Use topics to view posts that interest you and meet other people with similar interests.</p>
				</div>
				<div class="nextWelcome">Next</div>
			</div>
			<div class="slide">
				<h4>Menu</h4>
				<img src="images/screen5.jpg" alt="screenshot of vancity social" />
				<div id="welcomeParagraphs">
					<p>Everything else you need is up here. View your inbox, recent notifications, search for friends, or click on your name to edit your account.</p>
				</div>
				<div class="nextFinished">Finished</div>
			</div>
		</div>
	</div>
</div>