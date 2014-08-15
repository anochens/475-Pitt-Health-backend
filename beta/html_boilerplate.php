<?php

	/* print_boilerplate function
	 * 
	 * The purpose of this fuction is to print out all the necessary
	 * boilerplate for each HTML page within the site.
	 *
	 */
	function print_boilerplate($page_title) {

		echo "<!doctype html>
				<head>

					<link rel='stylesheet' type='text/css' href='styles/main.css'>
					<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
					<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>

				</head>

				<title>{$page_title} --- MyHealthSites</title>

			</head>";

	}

	function print_header() {

		echo "<div id='header'>
				<!--<h1 id='mainheader'>MyHealthSites</h1>-->
				<div id='headerimgdiv'><img id='headerimg' src='myHealthSitesImgs/logo_cartoony.jpg' alt='My Health Sites'></div>
		  	  </div>";

	}

	function print_navbar() {
      ?>
		<div id='navbar'>
				<div id='sub_navbar'><a id='homelink' href='index.php'>home</a> <span id='pipe'>|</span> <a id='aboutlink'>about us</a> <span id='pipe'>|</span> <a id='serviceslink'>services</a> <span id='pipe'>|</span> <a id='howworkslink'>how it works?</a></div>
		</div>
      <?php
	}

	function print_searchbar() {

		// Print out the search bar and FIND button
		?>
		<div id='search_bar_wrapper'>
			<form id='searchbar' action='results.php' method='get' name='searchbar'>

			<input id='search_text_box' type='text' name='q' placeholder='Diabetes, Asthma, Heart ... More' required>
			<input id='search_button' type='submit' name='search' value='FIND'>

		  </form></div>
		  <?php

	}

	function print_doctor() {

		echo "<div id= doctor_picture_div><img id=cartoon_doctor_img src= 'myHealthSitesImgs/cartoonDoctor.png' alt= 'cartoon doctor'> </div>";

	}

	function print_footer() {

		// Print out the footer with social outlet logos
		echo "<div class='footer' id = footer_bar><div id = centered_footer><span id='legal'>Copyright &copy; 2013 - MyHealthSites.com | All Rights Reserved</span>
				<div id='socialoutlets'>
					<span id='facebook_logo'><a href='http://www.facebook.com/myhealthsites' target='_blank'><img src='myHealthSitesImgs/facebook.png'></a></span>
					<span id='linkedin_logo'><img src='myHealthSitesImgs/linkedin.png'></span>
					<span id='youtube_logo'><img src='myHealthSitesImgs/youtube.png'></span>
					<span id='twitter_logo'><a href='https://twitter.com/myhealthsites' target='_blank'><img src='myHealthSitesImgs/twitter.png'></a></span>
					<span id='rss_logo'><img src='myHealthSitesImgs/rss.png'></span>
					<span id='googleplus_logo'><img src='myHealthSitesImgs/g+.png'></span>
				</div></div></div>";

	}

	function print_our_goal() {
		echo "<div id='our_goal'>Our goal is to help you find quality, up-to-date medical information and to help dispel common myths about various 
				medical conditions.</div>";

	}

	function print_main_wrapper() {
		echo "<div id='main_wrapper'>";
	
	}

?>
