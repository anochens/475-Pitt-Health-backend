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

	//for pagination. will need to be fixed for division
	$query = '';
	if(array_key_exists('q',$_GET))
		$query = urlencode($_GET['q']);
	if(!isset($_GET['start'])) {
		$start = urlencode("1");
	} else {
		$start = urlencode($_GET['start']);
	}   


	if(array_key_exists('advanced_search_indicator', $_REQUEST) &&
		$_REQUEST['advanced_search_indicator'] == '1') {
   	processFiltering();
	}

	function processFiltering() {
		$filteredData = array();
		$pattern = '/^(IAmA|personalize)_(\d+)i$/';
		$captures = array();

		//create a mapping of categories to their respective sites
		//from the data in the database
		$db = db_connect();
		$q = 'SELECT url, categories FROM searchable_sites';
		$sites = runQuery($q, $db);
      
		$cats_to_sites = array();

		foreach($sites as $site) {
      	$cats = explode(',', $site['categories']);

			foreach($cats as $cat) {
				if(!$cat) continue;
         	if(!array_key_exists($cat, $cats_to_sites)) {
            	$cats_to_sites[$cat] = array();
				}
				$cats_to_sites[$cat][] = $site['url'];
			}
		}

		//now remove the ones that we do not want
		$good_arr = array();
		$keepers = array(100, '1');

		foreach($_REQUEST as $k => $v) {

			if(!preg_match($pattern, $k, $captures)) {
				print "skipped $k<br>\n";
				continue; //skip bad lines
			}

			$id_of_cat = intval($captures[2]);

			if(in_array($v, $keepers)) {
				$good_arr[$id_of_cat] = $cats_to_sites[$id_of_cat];
			}

			$captures = array();
		}

      //now create a section for each good one
	}





	// Code for getting the results from the Google Custom Search Engine
	$url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyDBzCfhslTSWG6hVgaZ9eFgVqc1Ck5jxRE&cx=013942562424063258541:ofu8c_sygk4&q={$query}&start={$start}&callback=json";
	$results = execute_search($url);

	$pageNext = 2;
	$pagePrev = 0;
	
	if($results) {


		if(array_key_exists('queries', $results)) {
			$q = $results['queries'];

			if(array_key_exists('nextPage', $q)) {
				$pageNext = $q['nextPage'][0]['startIndex'];
			}
			
			if(array_key_exists('previousPage', $q)) {
				$pagePrev = $q['previousPage'][0]['startIndex'];
			}
		}
		
		if(array_key_exists('searchInformation', $results)) {

			$searchTime = $results['searchInformation']['formattedSearchTime'];

			$totalResults = $results['searchInformation']['formattedTotalResults'];
		}
	}

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

		if(isset($totalResults)) {

			echo "<div id='result_info'>About {$totalResults} ({$searchTime} seconds)</div>";
		}

?>

		<div class='generic_bar'>
			<a href='#'>Simple</a> <span>|</span>
			<a href='#'>Wizard</a>
		</div> 

<?php


		// Wrap the recommended sites in its own section
		echo "<div id='recommended_section'>";

			// Print out the recommended sites header
			echo "<div id='recommended_header'>Our recommended sites</div>";

			/*
			// Holds the recommended title
			$rec_result_title = $results['items'][0]['title'];
			// Holds the recommended link
			$rec_result_link = $results['items'][0]['link'];
			// Holds the recommneded formatted link
			$rec_formatted_link = $results['items'][0]['formattedUrl'];
			// Holds the recommended snippet
			$rec_result_snippet = $results['items'][0]['snippet'];

			echo "<div id='result'>
						<a id='title_link' href='{$rec_result_link}'><span id='result_title'>{$rec_result_title}</span></a><br>
						<a id='result_link' href='{$rec_result_link}'>{$rec_formatted_link}</a><br>
						<span id='result_snip'>{$rec_result_snippet}</span>
					  </div>";

			echo "<script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
				<!-- search ad -->
				<ins class='adsbygoogle'
//   			style='display:inline-block;width:728px;height:90px'
//   			data-ad-client='ca-pub-3723102550752370'
//   			data-ad-slot='7469889246'></ins>
				<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
				</script>";
			echo "<script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
					<!-- test ad -->
					<ins class='adsbygoogle'
					     style='display:inline-block;width:970px;height:90px'
					     data-ad-client='ca-pub-3723102550752370'
					     data-ad-slot='6194219644'></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>";
			echo "<div id='empty_space'></div>";
			echo "<script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
					<!-- test ad -->
					<ins class='adsbygoogle'
					     style='display:inline-block;width:970px;height:90px'
					     data-ad-client='ca-pub-3723102550752370'
					     data-ad-slot='6194219644'></ins>
					<script>
					(adsbygoogle = window.adsbygoogle || []).push({});
					</script>";

			*/
			echo "<div id='ads'>";
			echo "<script type='text/javascript'><!--
					google_ad_client = 'ca-pub-3723102550752370';
					/* test ad */

					google_ad_slot = '6194219644';
					google_ad_width = 970;
					google_ad_height = 90;
					//-->
					</script>
					<script type='text/javascript'
					src='//pagead2.googlesyndication.com/pagead/show_ads.js'>
					</script>";
			echo "</div>";
		// Close the recommended sites section
		echo "</div>";

		// Wrap the general medical search in its own section
		echo "<div id='general_search_section'>";

			// Print out 'General Medical Search' header
			echo "<div id='general_header'>General Medical Search</div>";

			if(isset($result) && array_key_exists('items', $results) && count($results['items']) > 0) {

			// Loop that prints out each result that was returned, default here will be 10
			foreach ($results['items'] as $result) {
				$result_title = $result['title'];
				$result_link = $result['link'];
				$formatted_link = $result['formattedUrl'];
				$result_snippet = $result['snippet'];
				// Print out each result title, link, and snippet
				echo "<div id='result'>
						<a id='title_link' href='{$result_link}'><span id='result_title'>{$result_title}</span></a><br>
						<a id='result_link' href='{$result_link}'>{$formatted_link}</a><br>
						<span id='result_snip'>{$result_snippet}</span>
					  </div>";
			}

			echo "<div id='forward_back'>";
			echo "<span>";
			
			if($pagePrev > 0) {
				echo "<a id='prev_page' href='results.php?q={$query}&start={$pagePrev}'>Previous</a>";
			}
			else {
				echo "Previous";
			}
			
			echo "&nbsp;</span>";

			for($i=1;$i<=10;$i++) {
				$startLocal = (($i-1)*10)+1;

				if($i == 0) $i='Previous';
				if($start == $startLocal) {
            	echo $i;
				}          
				else {
					echo "<span><a id='page' href='results.php?q={$query}&start=$startLocal'>$i</a></span>";
				}
			}

			echo "<span>";
			if($pageNext < 100) { //no more results
				echo "<a id='next_page' href='results.php?q={$query}&start={$pageNext}'>Next</a>";
			}
			else {
         	echo '&nbsp;Next';
			}
			echo "</span></div>";
		}
		else { //no results
			echo "<span class='error'>Sorry, there are no results to display.</span>";

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
