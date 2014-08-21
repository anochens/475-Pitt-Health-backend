<?php

include_once('admin/functions.php');

$url_q = urlencode($_GET['q']);
$resultsOffset = '1';
if(array_key_exists('start', $_GET)) {
	$resultsOffset = intval($_GET['start']);
}                          

$section_num = 1;
if(array_key_exists('section_num', $_GET)) {
	$section_num = intval($_GET['section_num']);
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
    

$section_name = runQuery('SELECT name FROM search_categories WHERE id='.$section_num);
$section_name = $section_name[0]['name'];

if($sites) {
	$sites = runQuery("SELECT url from searchable_sites WHERE id IN($sites)", $db, true, false);

	$sites = json_encode($sites);
}


?>




<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script>                      


var resultsCache = [];
var resultsCache_i = 0;

function formatResults(results) {
   all_results = [];

	for(i=0;i<results.length;i++) {
      result = results[i];

		result_title = result['title'];
		result_link = result['link'];
		formatted_link = result['formattedUrl'];
		result_snippet = result['snippet'];
		// Print out each result title, link, and snippet
		
		resultFormatted = '';
		resultFormatted +="<div class='.result' id='result'>"; 
		resultFormatted +="	<a id='title_link' href='"+result_link+"'><span id='result_title'>"+result_title+"</span></a><br>"; 
		resultFormatted +="	<a id='result_link' href='"+result_link+"'>"+formatted_link+"</a><br>"; 
		resultFormatted +="	<span id='result_snip'>"+result_snippet+"</span>";
		resultFormatted +="	</div>";

 		all_results.push(resultFormatted);
	}

	return all_results.join(" <br/>\n\n");
}


function getResultsFromCache(num_results) {

	//if there aren't enough results left in the cache
	if(resultsCache.length <= resultsCache_i+num_results) {
		refillCache();
	}

	//we reached the end, there are no more results to populate the cache with
   if(resultsCache.length < num_results) {
   	return [];
	}

	//if we get here, the cache is successfully populated with enough
	//results that we can return them

	results = resultsCache.slice(resultsCache_i, resultsCache_i + num_results);
	resultsCache_i += num_results; //move cache indexer over

	return results;
}

function execSearch(url) {
	items = [];
	$.ajax({
   	url: url,
		async: false,
		complete: function(data) {
			json_obj = data.responseText;

			// json decode the json object into an array like structure 
			results = JSON.parse(json_obj);

			if(items) {
				items = results.items;
			}
		}
	});
	return items;
}


var key = "AIzaSyDBzCfhslTSWG6hVgaZ9eFgVqc1Ck5jxRE&";
var cx = "013942562424063258541:ofu8c_sygk4&";
                      
var cache_refill_size = 10;
var site_start_i = 0; //which site in the site string to start the query at
var site_num_include = 10; //how many sites to include in the url

var base_search_string = "https://www.googleapis.com/customsearch/v1?key="+key+"&cx="+cx+"&num="+cache_refill_size;

//passed in
var sites = <?= $sites ?>;
var q = '<?= $url_q ?>';
var resultsOffset = '<?= $resultsOffset ?>';

function urlencode(str) {
	//discuss at: http://phpjs.org/functions/urlencode/

	str = (str + '')
		.toString();

	return encodeURIComponent(str)
		.replace(/!/g, '%21')
		.replace(/'/g, '%27')
		.replace(/\(/g, '%28')
		.replace(/\)/g, '%29')
		.replace(/\*/g, '%2A')
		.replace(/%20/g, '+');
}

//reduces the scope of search to just how this string specifies
function getSiteString() {
	if(!sites) return "";

	subarr = sites.slice(site_start_i, site_start_i+site_num_include);
	
	sitestring = subarr.join(' || ');
	sitestring = ' sites('+sitestring+')';

	sitestring = urlencode(sitestring);
	return sitestring;
}

function refillCache() {

	//with current string get more results

	// Code for getting the results from the Google Custom Search Engine

	sitestring = getSiteString();
	new_items = execSearch(base_search_string+"&start="+resultsOffset+"&q="+q+sitestring);

	//if new results were problematic
   if(!new_items || new_items.length < cache_refill_size) {
		//move sites filter up
		site_start_i += site_num_include;

		//break if no more sites to search through
		if(site_start_i >= sites.length) return;

		resultsOffset = 0;

		//call function again to refill with the new vars
		refillCache();
	}
	else {
		//add items into the cache
		resultsCache = resultsCache.concat(new_items);
	}
}



</script>
