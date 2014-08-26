<?php

	include_once('admin/functions.php');
	include_once('result_functions.php');

	$query = urlencode($_GET['q']);
	if(!isset($_GET['start'])) {
		$start = '1';
	} else {
		$start = intval($_GET['start']);
	}   

	$section_num = 1;
	if(array_key_exists('section_num', $_GET)) {
		$section_num = intval($_GET['section_num']);
	} 

	if(array_key_exists('num', $_GET)) {
		$num = intval($_GET['num']);
	}
	else { 
		$num=10;
	}

	$sites = ''; //blank is all sites in search engine
	$pattern = '/^((\d+),?)+$/';
	if(array_key_exists('sites',$_GET)) {
		$sites = $_GET['sites'];

		if(!preg_match($pattern, $sites)) {
			print('Invalid site specifier');
			$sites='';
		}
	}



   $title = 'Generic';
   $section_name = runQuery('SELECT name FROM search_categories WHERE id='.$section_num);
	$section_name = $section_name[0]['name'];


	echo "<div id='sub_main_wrapper_results'>";

	echo "<div id='sub_content_wrapper'>";


		?>
      <script>
		function togglePlusMinus(section_num) {

			me = $("#result_wrapper"+section_num);
			me.toggle();

			$("#general_search_section"+section_num+" button").toggle();

			myimg = $("#general_search_section"+section_num+" img:eq(0)");

			if(myimg.attr("src").indexOf("minus") > -1) {
				myimg.attr("src", "img/plus.png");
			}
			else {
				myimg.attr("src", "img/minus.png");
			}
		}
		
		function reloadFor(section_num) {
			results = getFormattedResults(3, section_num);

			if(!results) { 
				results = '<span class="error">Sorry, there are no results to display</span>';

			}
			$('#result_wrapper'+section_num).html(results);
		}
//			echo "$('#results$section_num .result_wrapper').html('<h1><center>Loading</cener></h1>');";

		</script>  	                    


		<?php


		echo "<div class='general_search_section' id='general_search_section$section_num' class='general_search_section'>";

			$divid = 'general_header';
			// Print out title header
			if($section_name == 'Topic Overview') {
				$divid='generic_header';
				$section_name = 'Overview to Topic';
			}
			echo "<div class='$divid' onclick='togglePlusMinus($section_num);'>$section_name";

			if($section_name != 'Overview to Topic') {
				echo "<img class='plusminus' id='plusminus$section_num'  src='img/minus.png' />";
			}

			echo "
			
			</div>

			<script>
			$(document).ready(function() {
				reloadFor($section_num);
			});
			</script>

			
			<div class='result_wrapper' id='result_wrapper".$section_num."'>
			</div>

			<button class='more_results_btn' onclick='reloadFor($section_num)'>more >></button>";
			



		//close content wrapper
		echo "</div>"; 

	// Close the main page wrapper
	echo "</div>";

die;
