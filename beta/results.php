<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<?php

	// Set error display to on, this will give a useful page error
	// rather than just a 500 bad request error
	ini_set('display_errors','On');

	include("executesearch.php");
	include('admin/functions.php');
	include('html_boilerplate.php');

	$page_title = "Search Results";

	print_boilerplate($page_title);

	echo "<body>";
 
    
	print_header();

	print_navbar();

	echo "<div id='main_wrapper_results'>";

	echo "<div id='content_wrapper'>";

	print_our_goal();

	print_searchbar();





	if(array_key_exists('advanced_search_indicator', $_REQUEST) &&
		$_REQUEST['advanced_search_indicator'] == '1') {
   	processFiltering();
	}
	else {
   	redir("result_nofilter.php", true);
	}
    


	function processFiltering() {
		$filteredData = array();
		$pattern = '/^(IAmA|personalize)_(\d+)$/';
		$captures = array();

		//create a mapping of categories to their respective sites
		//from the data in the database
		$db = db_connect();
		$q = 'SELECT id, categories FROM searchable_sites';
		$sites = runQuery($q, $db);
      
		$cats_to_sites = array();

		foreach($sites as $site) {
      	$cats = explode(',', $site['categories']);

			foreach($cats as $cat) {
				if(!$cat) continue;
         	if(!array_key_exists($cat, $cats_to_sites)) {
            	$cats_to_sites[$cat] = array();
				}
				$cats_to_sites[$cat][] = $site['id'];
			}
		}



		$not_iama_cats = runQuery('SELECT id FROM search_categories WHERE is_iama=0', $db, true, false);



		//now remove the ones that we do not want
		$good_arr = array();

		foreach($_REQUEST as $k => $v) {

			if(!preg_match($pattern, $k, $captures)) {
				continue; //skip bad lines
			}

			$id_of_cat = intval($captures[2]);

			if(intval($v)>=50 || $v == '1') {
				$good_arr[$id_of_cat] = $cats_to_sites[$id_of_cat];
			}

			$captures = array();
		}
		$query = urlencode($_GET['q']);

		echo "<div id='results_leftside'>";

      //now create a section for each good one
		foreach($good_arr as $k => $v) {
			$sites = implode(',',$v);

			$good_arr[$k] = "result_section.php?q=$query&section_num=$k&num=3&sites=$sites";
			if(!in_array($k, $not_iama_cats)) {
				echo "<span id='results{$k}_url' style='visibility:hidden'>".$good_arr[$k]."</span>";
         	unset($good_arr[$k]);
				continue;
			}           	
			echo "<div id='results{$k}'></div>";
		}

		?>

		</div> <!-- left side -->
	<script>
		$(document).ready(function() {
      
		<?php
		echo "
				$('#searchbar').submit(function() {

					searchstring = 'results.php?submit=submit&advanced_search_indicator=1&q='+$('#search_text_box').val() ;

					";
					$pattern = '/^(IAmA|personalize)_(\d+)$/';

					foreach($_REQUEST as $k => $v) {
						if(preg_match($pattern, $k)) {
							echo "searchstring += '&".$k.'='.$v."';\n\t\t\t\t\t";
						}
					}

            echo "	
					window.location = searchstring;
				});\n\n"; 


		//fill the sections
		foreach($good_arr as $k => $v) {
			echo "\t\t\t\t$('#results$k').load('$v');\n";
		}           
		?>



		});
	</script>

	<div id='results_rightside'>
		<div class='generic_header'>Need health insurance?</div>

		<div class='rightside_box' id='insurance_box'>
			<div class='sub_header'>Quotes by phone*</div>

			<table>
				<tr><td style='width:10%'>Name </td><td><input style='width:48%' name='insurance_first' value='First name'> <input style='width:48%' name='insurance_last' value='Last name'></td></tr>
			</table><table>
				<tr><td style='width:25%'>Country Code</td><td><input size=5 name='insurance_country'></td></tr>
				<tr><td>Phone</td><td><input style='width:100%' name='insurance_phone' value='Phone'></td></tr>
				<tr><td>Language</td><td><input style='width:100%' name='insurance_language' value='Select Language'></td></tr>
				<tr><td>Call when</td><td><input size=5 name='insurance_call_when' value='Now'></td></tr>
			</table>
		</div>


	</div>


	<style>
	#results_rightside .generic_header {
		margin-bottom: 0px;

	}
	#results_leftside {
		float:left;
		width:700px;

	} 
 	#results_rightside {
		float:right;
		border:solid;
		height: 1000px;
		width:350px;

		position:relative;
		left: -50px;

		display:inline;
	} 

	#insurance_box {
   	color:white;
		background-color:green;
	}

	.sub_header {
   	font-size: 26px;
		margin-top: 5px;
		margin-bottom: 10px;
	}
	
	.rightside_box td {
   	color:white;
		padding-bottom:10px;
	}

	.rightside_box {
		padding: 10px;
	}

	</style>

		<?php

	}


		// Close the general medical search section
		echo "</div>";

		//close content wrapper
		echo "</div>"; 

	// Close the main page wrapper
	echo "</div>";

	// Print the page footer
	print_footer();
?>
