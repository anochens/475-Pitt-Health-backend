<?php

	ini_set('display_errors','On');

	include("html_boilerplate.php");

	$page_title = "Home";

	print_boilerplate($page_title);

	echo "<body>";

	print_header();

	print_navbar();

	print_main_wrapper();

	?>
	<div id='content_wrapper'>

	<fieldset style='width:400px;'>
	<legend>Batch insert/delete</legend>

	<?php

	include('update_searchable_sites.php');
   ?>

	</fieldset>

	<?php


	include('results_editor.php');

	//print_our_goal();

	// start the main body of the page
	//print_searchbar();

	//print_doctor();

	// close the main wrapper div
	echo "</div>";

	// Close the main wrapper
	echo "</div>";

	print_footer();

	echo "</body>";
?>
