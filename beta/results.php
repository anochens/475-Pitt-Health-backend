<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<?php

	// Set error display to on, this will give a useful page error
	// rather than just a 500 bad request error
	ini_set('display_errors','On');

	include("executesearch.php");
	include('admin/functions.php');

	//make it very easy to switch between adult and not
	if(isset($adult) && $adult) {
		include("adult/html_boilerplate.php");
	}	
	else {
		include("html_boilerplate.php");
	}

	$page_title = "Search Results";

	print_boilerplate($page_title);

	echo "<body>";
 
    
	print_header();

	print_navbar();

	echo "<div id='main_wrapper_results'>";

	echo "<div id='content_wrapper'>";

	print_our_goal();

	print_searchbar();

?>

		<div class='generic_bar'>
		<ul>
			<li><a href='#'>Video</a></li> 
			<li><a href='#'>Images</a></li> 
			<li><a href='#'>Research</a></li> 
			<li><a href='#'>Forums</a></li> 
			<li><a href='#'>Blog</a></li> 
			<li><a href='#'>Positions</a></li> 
		</ul>
		</div>                


<?php


		//echo "<div id='result_info'>About {$totalResults} ({$searchTime} seconds)</div>";

?>

<br/><br/>
		<div class='generic_bar'>
			<a href='#'>Simple</a> <span>|</span>
			<a href='#'>Wizard</a>
		</div> 
 

<?php


	if(array_key_exists('advanced_search_indicator', $_REQUEST) &&
		$_REQUEST['advanced_search_indicator'] == '1') {
   	processFiltering();
	}
	else {
   	redir("result.php.backup", true);
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

      //now create a section for each good one
		foreach($good_arr as $k => $v) {
			$sites = implode(',',$v);

			if(in_array($k, $not_iama_cats)) {
				$good_arr[$k] = "result_section.php?q=$query&section_num=$k&num=3&sites=$sites";
			}
			else {
         	unset($good_arr[$k]);
				continue;
			}           	
			echo "<div id='results{$k}'></div>";
		}

		?>
	<script>
		$(document).ready(function() {
      
		<?php

		//fill the sections
		foreach($good_arr as $k => $v) {
                                    /*
			$numbers = '';
			for($i=1;$i<=10;$i++) {
				$numbers .= "<button onclick=\"$(\'#results$k\').load(\'$v&start=".((($i-1)*10)+1)."\')\">$i</button>";

			}                            */                            


			echo "$('#results$k').load('$v', function() {
//			$('#results$k').append('$numbers');
				
			});\n\t\t";


		}           
		?>



		});
	</script>

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
