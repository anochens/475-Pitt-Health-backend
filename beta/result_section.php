<?php 

include_once('html_boilerplate.php');
print_boilerplate();

?>


<?php 
$adult = checkAdultStatus();
if($adult == 'clinical') $adult='_adult';
else $adult='';
$q='';
	if(array_key_exists('q',$_GET)) {
   	$q = htmlentities($_GET['q']);
	}

?>
<style>
iframe {
	height:100%;
	width:100%;
	overflow:hidden;
	padding-bottom:0px;
	margin-bottom:0px;
	border:none;


}

</style>
<script>

		function reloadFor(section_num) {
			/*results = getFormattedResults(3, section_num);

			if(!results) { 
				results = '<span class="error">Sorry, there are no results to display</span>';

			}
			//$('#result_wrapper'+section_num).html(results);
	 */  }         

</script>

<?php

	include_once('admin/functions.php');

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
			//print('Invalid site specifier');
			$sites='';
		}
	}



   $title = 'Generic';
   $section_name = runQuery('SELECT name FROM search_categories WHERE id='.$section_num);
	if(count($section_name) > 0) {
		$section_name = $section_name[0]['name'];
	}
	else {
   	$section_name = $section_num.': '.print_r($section_name, true);
	}


	echo "<div id='sub_main_wrapper_results'>";

	echo "<div id='sub_content_wrapper'>";


		?> 	                    


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
				echo "<img class='plusminus' id='plusminus$section_num'  src='img/minus$adult.png' />";
			}

			echo "
			
			</div>

			<script>
			$(document).ready(function() {
				reloadFor($section_num);
			});
			</script>

			
			<div class='result_wrapper' id='result_wrapper".$section_num."'>
				<iframe class='result_wrapper' src='gresults.php?q=$q' scrolling='no'>
			</div>

			<button class='more_results_btn' onclick='reloadFor($section_num)'>more >></button>";
			



		//close content wrapper
		echo "</div>"; 

	// Close the main page wrapper
	echo "</div>";

?>
<script>
$(document).ready(function() {
	cursor_val = $($('.gsc-cursor-current-page')[0]).html();
	console.log(cursor_val);

});

</script>         

