<?php


	// Set error display to on, this will give a useful page error
	// rather than just a 500 bad request error
	ini_set('display_errors','On');

	include_once("executesearch.php");
	include_once('admin/functions.php');
	include_once('html_boilerplate.php');

	$page_title = "Search Results";
	print_boilerplate($page_title);

	echo "<body>";

	$query = '';
	if(array_key_exists('q',$_GET))
		$query = urlencode($_GET['q']);
	if(!isset($_GET['start'])) {
		$start = urlencode("1");
	} else {
		$start = urlencode($_GET['start']);
	}   

	// Code for getting the results from the Google Custom Search Engine
	$url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyDBzCfhslTSWG6hVgaZ9eFgVqc1Ck5jxRE&cx=013942562424063258541:ofu8c_sygk4&q={$query}&start={$start}&callback=json";
	$results = execute_search($url);

	$pageNext = 2;
	$pagePrev = 0;
	$searchTime = 0;
	$totalResults = 0;
	
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

		print_searchbar(true);

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

			echo "<div id='ads'>";
			echo "<script type='text/javascript'><!--
					google_ad_client = 'ca-pub-3723102550752370';

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

			if(isset($results) && array_key_exists('items', $results)) {

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

<style>
#search_bar_wrapper { width:600px; }

</style>            
