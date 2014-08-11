<?php

	ini_set('display_errors','On');

	include("html_boilerplate.php");

	$page_title = "Home";

	print_boilerplate($page_title);

	echo "<body>";

	// print out the header
	print_header();

	// print out the nav bar
	print_navbar();

	// print out index's wrapper
	print_main_wrapper();

	echo "<div id=\"content_wrapper\">";

	// Print out our goal
	print_our_goal();

	// start the main body of the page
	print_searchbar();

	print_doctor();

	echo "</div>";

	// Close the main wrapper
	echo "</div>";

	// Call function to print the footer
	print_footer();

	echo "</body>";
?>