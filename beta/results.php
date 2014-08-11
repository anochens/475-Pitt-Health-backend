<?php

	// Set error display to on, this will give a useful page error
	// rather than just a 500 bad request error
	ini_set('display_errors','On');

	// Include the necessary PHP files
	include("executesearch.php");
	include("html_boilerplate.php");

	// Provide the page title
	$page_title = "Search Results";

	// Print the top boilerplate
	print_boilerplate($page_title);

	// Start the body section of the HTML
	echo "<body>";

	// Code for getting the GET requests from the browser
	$query = urlencode($_GET['q']);
	if(!isset($_GET['start'])) {
		$start = urlencode("1");
	} else {
		$start = urlencode($_GET['start']);
	}

	// Code for getting the results from the Google Custom Search Engine
	$url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyDBzCfhslTSWG6hVgaZ9eFgVqc1Ck5jxRE&cx=013942562424063258541:ofu8c_sygk4&q={$query}&start={$start}&callback=json";
	// This will hold the entire JSON object returned from Google
	$results = execute_search($url);
	// This holds which page of results is next
	$pageNext = $results['queries']['nextPage'][0]['startIndex'];
	// This holds which page of results was previous
	$prevPage = $results['queries']['previousPage'][0]['startIndex'];
	// This holds the amount of results found
	$searchTime = $results['searchInformation']['formattedSearchTime'];
	// This holds the time it took to search
	$totalResults = $results['searchInformation']['formattedTotalResults'];

	// Print out the header
	print_header();

	// Print out the nav bar
	print_navbar();

	// Print the main page wrapper
	echo "<div id=\"main_wrapper_results\">";

		// Print our our goal message
		print_our_goal();

		// Print out the search bar
		print_searchbar();

		echo "<div id=\"result_info\">About {$totalResults} ({$searchTime} seconds)</div>";

		// Wrap the recommended sites in its own section
		echo "<div id=\"recommended_section\">";

			// Print out the recommended sites header
			echo "<div id=\"recommended_header\">Our recommended sites</div>";

			// // Holds the recommended title
			// $rec_result_title = $results['items'][0]['title'];
			// // Holds the recommended link
			// $rec_result_link = $results['items'][0]['link'];
			// // Holds the recommneded formatted link
			// $rec_formatted_link = $results['items'][0]['formattedUrl'];
			// // Holds the recommended snippet
			// $rec_result_snippet = $results['items'][0]['snippet'];

			// echo "<div id=\"result\">
			// 			<a id=\"title_link\" href=\"{$rec_result_link}\"><span id=\"result_title\">{$rec_result_title}</span></a><br>
			// 			<a id=\"result_link\" href=\"{$rec_result_link}\">{$rec_formatted_link}</a><br>
			// 			<span id=\"result_snip\">{$rec_result_snippet}</span>
			// 		  </div>";

			// echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
			// 	<!-- search ad -->
			// 	<ins class=\"adsbygoogle\"
   //   				style=\"display:inline-block;width:728px;height:90px\"
   //   				data-ad-client=\"ca-pub-3723102550752370\"
   //   				data-ad-slot=\"7469889246\"></ins>
			// 	<script>
			// 		(adsbygoogle = window.adsbygoogle || []).push({});
			// 	</script>";
			// echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
			// 		<!-- test ad -->
			// 		<ins class=\"adsbygoogle\"
			// 		     style=\"display:inline-block;width:970px;height:90px\"
			// 		     data-ad-client=\"ca-pub-3723102550752370\"
			// 		     data-ad-slot=\"6194219644\"></ins>
			// 		<script>
			// 		(adsbygoogle = window.adsbygoogle || []).push({});
			// 		</script>";
			echo "<div id=\"empty_space\"></div>";
			// echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
			// 		<!-- test ad -->
			// 		<ins class=\"adsbygoogle\"
			// 		     style=\"display:inline-block;width:970px;height:90px\"
			// 		     data-ad-client=\"ca-pub-3723102550752370\"
			// 		     data-ad-slot=\"6194219644\"></ins>
			// 		<script>
			// 		(adsbygoogle = window.adsbygoogle || []).push({});
			// 		</script>";
			echo "<div id=\"ads\">";
			echo "<script type=\"text/javascript\"><!--
					google_ad_client = \"ca-pub-3723102550752370\";
					/* test ad */
					google_ad_slot = \"6194219644\";
					google_ad_width = 970;
					google_ad_height = 90;
					//-->
					</script>
					<script type=\"text/javascript\"
					src=\"//pagead2.googlesyndication.com/pagead/show_ads.js\">
					</script>";
			echo "</div>";
		// Close the recommended sites section
		echo "</div>";

		// Wrap the general medical search in its own section
		echo "<div id=\"general_search_section\">";

			// Print out 'General Medical Search' header
			echo "<div id=\"general_header\">General Medical Search</div>";

			// Loop that prints out each result that was returned, default here will be 10
			foreach ($results['items'] as $result) {
				// Holds the result title
				$result_title = $result['title'];
				// Holds the result link
				$result_link = $result['link'];
				// Hold the formatted link
				$formatted_link = $result['formattedUrl'];
				// Hold the result snippet
				$result_snippet = $result['snippet'];
				// Print out each result title, link, and snippet
				echo "<div id=\"result\">
						<a id=\"title_link\" href=\"{$result_link}\"><span id=\"result_title\">{$result_title}</span></a><br>
						<a id=\"result_link\" href=\"{$result_link}\">{$formatted_link}</a><br>
						<span id=\"result_snip\">{$result_snippet}</span>
					  </div>";
			}

			echo "<div id=\"forward_back\"><span><a id=\"prev_page\" href=\"/results.php?q={$query}&start={$prevPage}\">Previous</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}\">1</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=10\">2</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=20\">3</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=30\">4</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=40\">5</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=50\">6</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=60\">7</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=70\">8</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=80\">9</a></span>
				<span><a id=\"page\" href=\"/results.php?q={$query}&start=90\">10</a></span>
				<span><a id=\"next_page\" href=\"/results.php?q={$query}&start={$pageNext}\">Next</a></span></div>";

		// Close the general medical search section
		echo "</div>";

	// Close the main page wrapper
	echo "</div>";

	// Print the page footer
	print_footer();

	// Close the body section of the URL
	echo "</body>";

?>