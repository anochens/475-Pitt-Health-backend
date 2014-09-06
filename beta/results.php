<?php
	// Set error display to on, this will give a useful page error
	// rather than just a 500 bad request error
	ini_set('display_errors','On');

	include_once("executesearch.php");
	include_once('admin/functions.php');
	include_once('html_boilerplate.php');

	$page_title = "Search Results";


	if(array_key_exists('advanced_search_indicator', $_REQUEST) &&
		$_REQUEST['advanced_search_indicator'] == '1') {
		print_boilerplate($page_title);
	}
	else {
   	redir("result_nofilter.php", true);
	} 


	echo "<body>";
 
    
	print_header();

	print_navbar();

	echo "<div id='main_wrapper_results'>";

	echo "<div style='width:1200px' id='content_wrapper'>";
	processFiltering();

	//print_our_goal();

	//print_searchbar();




    


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
		$all_arr = array();

		foreach($_REQUEST as $k => $v) {

			if(!preg_match($pattern, $k, $captures)) {
				continue; //skip bad lines
			}

			$id_of_cat = intval($captures[2]);

			if(intval($v)>=50 || $v == '1') {
				$good_arr[$id_of_cat] = $cats_to_sites[$id_of_cat];
			}
			$all_arr[$id_of_cat] = $cats_to_sites[$id_of_cat];

			$captures = array();
		}
		$query = urlencode($_GET['q']);

		echo "<div id='results_leftside'>";

      //now create a section for each good one
		foreach($all_arr as $k => $v) {
			$sites = implode(',',$v);

			$good_arr[$k] = "result_section.php?q=$query&section_num=$k&num=3&sites=$sites";
			if(!in_array($k, $not_iama_cats)) {
				echo "<span id='results{$k}_url' style='visibility:hidden'>".$good_arr[$k]."</span>";
         	unset($good_arr[$k]);
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

					searchstring = 'results.php?submit=submit&advanced_search_indicator=1&q='+$('#search_text_box2').val() ;


					";
					$pattern = '/^(IAmA|personalize)_(\d+)$/';

					foreach($_REQUEST as $k => $v) {
						if(preg_match($pattern, $k)) {
							echo "searchstring += '&".$k.'='.$v."';\n\t\t\t\t\t";
						}
					}

            echo "	
					window.location = searchstring;
					return false;
				});\n\n"; 


		//fill the sections
		/*echo "</script>";
		var_dump($good_arr);
		die; */

		foreach($good_arr as $k => $v) {
			echo "\t\t\t\t$('#results$k').load('$v');\n";
		}           
		?>



		});
	</script>

	<div id='results_rightside'>

  		<div id='search_bar_wrapper2'>
			<form id='searchbar' style='height:30px' action='results.php' method='get' name='searchbar'>

			<input id='search_text_box2' type='text' name='q' placeholder='Diabetes, Asthma, Heart ... More'>
			<img id='search_button2' onclick='$("form").submit();' name='search' src='img/magglass.png'>   

			</form>
		</div>


		<div class='generic_header'>Need health insurance?</div>


		<div class='rightside_box' id='insurance_box'>
			<div class='sub_header'>Quotes by phone*</div>

			<table>
				<tr><td style='width:10%'>Name </td><td><input style='width:48%' name='insurance_first' placeholder='First name'> <input style='width:48%' name='insurance_last' placeholder='Last name'></td></tr>
			</table><table>
				<tr><td style='width:25%'>Country Code</td><td><input size=5 name='insurance_country'></td></tr>
				<tr><td>Phone</td><td><input style='width:100%' name='insurance_phone' placeholder='Phone'></td></tr>
				<tr><td>Language</td><td><input style='width:100%' name='insurance_language' placeholder='Select Language'></td></tr>
				<tr><td>Call when</td><td><input size=5 name='insurance_call_when' placeholder='Now'></td></tr>
			</table>
		</div>




		<div class='rightside_box' id='email_box'>
			<div class='sub_header'>Quotes by email</div>

			<table>
				<tr><td style='width:10%'>Name </td><td><input style='width:48%' name='insurance_first' placeholder='First name'> <input style='width:48%' name='insurance_last' placeholder='Last name'></td></tr>
			</table><table>
				<tr><td>Email</td><td><input style='width:100%' name='insurance_email' placeholder='e.g. name@healthsites.com'></td></tr>
			</table>

			Quotes by email qualify for health points<br>
			Get FREE health videos with health points
		</div>







		<div class='rightside_box' id='online_now_box'>
			<div class='sub_header'>Get Quotes Online Now</div>

			<table>
				<tr><td>Insurance type</td><td><input style='width:100%' name='insurance_type' placeholder='Insurance type'></td></tr>
				<tr><td style='width:25%'>Zip</td><td><input size=5 name='insurance_zip' placeholder='Zip'></td></tr>
			</table>
		</div>                                      






     
<!--		<div class='generic_header'>Personalize your search</div>
   -->

	<?php
	$sidebar = true;
	include_once('filtering_interface.php');
	?>




 

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
	  /* border:solid; */
		height: 1000px;
		width:320px;

		position:relative;
		left: -125px; 

		display:inline;
	} 
	#results_leftside { padding-top:32px}

	#insurance_box {
   	color:white;
		background-color:rgb(0,181,176);
	}
                                    
	#email_box {
		color:white;
   	background-color:rgb(113,0,93);
	}                                 

	#online_now_box {
		color:white;
   	background-color:rgb(0,1,95);
	}

	.sub_header {
   	font-size: 24px;
		margin-top: 5px;
		margin-bottom: 10px;
	}
	
	.rightside_box td {
   	color:white;
		padding-bottom:10px;
	}

	.rightside_box {
		padding: 10px;
		font-family: "Arial Rounded MT", Arial, Helvetica, sans-serif; 
		font-weight: bold; 
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
