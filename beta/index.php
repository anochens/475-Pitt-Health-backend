<?php

	ini_set('display_errors','On');

	include("html_boilerplate.php");

	$page_title = "Home";

	print_boilerplate($page_title);

	echo "<body>";

	print_header();

	print_navbar();

	print_main_wrapper();

	echo "<div id='content_wrapper'>";

	print_our_goal();

	// start the main body of the page
	print_searchbar();


	include('filtering_interface.php');

	print_doctor();

	// close the main wrapper div
	echo "</div>";

	// Close the main wrapper
	echo "</div>";

	print_footer();

	echo "</body>";
?>
