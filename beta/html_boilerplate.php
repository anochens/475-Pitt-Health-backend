<?php
	function checkAdultStatus() {
		global $view;

		$view = 'cartoony';
		if(array_key_exists('view', $_COOKIE)) {
			$view = $_COOKIE['view'];
		}

		if(array_key_exists('view', $_GET)) {
			$newview = $_GET['view'];

			//valid new view
			if(in_array($newview, array('clinical', 'cartoony'))) {
				setcookie('view', $newview);
				$view = $newview;
			}
		}
	}

	/* print_boilerplate function
	 * 
	 * The purpose of this fuction is to print out all the necessary
	 * boilerplate for each HTML page within the site.
	 *
	 */
	function print_boilerplate($page_title) {
		checkAdultStatus();
		global $view;

      ?>
		<!doctype html>
				<head>

					<link rel='stylesheet' type='text/css' href='styles/main.css'>
					
					<?php 
					if($view == 'clinical') {
						echo "<link rel='stylesheet' type='text/css' href='styles/adult.css'>";
					}  ?>

					<meta http-equiv='Content-Type' content='text/html;charset=utf-8' />
					<link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700' rel='stylesheet' type='text/css'>



		   	<title><?= $page_title ?> --- MyHealthSites</title>

			</head>
       <?php
	}

	function print_header() {
		global $view;

		$switchstring = ($view == 'cartoony') ? 'clinical' : 'casual';
		$switchstring .= ' view';
		$imgstring = ($view == 'cartoony') ? 'cartoony' : 'professional';
		$href = '?view=';
		$href .= ($view == 'cartoony') ? 'clinical' : 'cartoony';

		?>

			<div id='header'>
			<!--<h1 id='mainheader'>MyHealthSites</h1>-->
				<div id='headerimgdiv'><img id='headerimg' src='img/logo_<?= $imgstring ?>.jpg' alt='My Health Sites'></div>
				<div>
					<a id='view_switcher' href=<?= $href ?>><?= $switchstring ?></a>
				</div>

			</div>
			
			<?php

	}

	function print_navbar() {
      ?>
		<div id='navbar'>
				<div id='sub_navbar'><a id='homelink' href='index.php'>Home</a> <span id='pipe'>|</span> <a id='aboutlink'>About us</a> <span id='pipe'>|</span> <a id='serviceslink'>Services</a> <span id='pipe'>|</span> <a id='howworkslink'>How it works?</a></div>
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
		<?php
		print_doctor();  

		echo "  </form>  ";
		  
		  
		echo "</div>";

	}

	function print_doctor() {
		global $view;
		$cartoon = ($view == 'cartoony') ? 'cartoonD' : 'd';

		//echo "<div id= doctor_picture_div>";
		echo "<img id=cartoon_doctor_img src= 'img/".$cartoon."octor.png'>"; 
		//echo "</div>";

	}

	function print_footer() {

		// Print out the footer with social outlet logos
		echo "<div class='footer' id = footer_bar><div id = centered_footer><span id='legal'>Copyright &copy; 2013 - MyHealthSites.com | All Rights Reserved</span>
				<div id='socialoutlets'>
					<span id='facebook_logo'><a href='http://www.facebook.com/myhealthsites' target='_blank'><img src='img/facebook.png'></a></span>
					<span id='linkedin_logo'><img src='img/linkedin.png'></span>
					<span id='youtube_logo'><img src='img/youtube.png'></span>
					<span id='twitter_logo'><a href='https://twitter.com/myhealthsites' target='_blank'><img src='img/twitter.png'></a></span>
					<span id='rss_logo'><img src='img/rss.png'></span>
					<span id='googleplus_logo'><img src='img/g+.png'></span>
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
