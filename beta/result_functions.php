
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
if(count($section_name) > 0) {
	$section_name = $section_name[0]['name'];
}

if($sites) {
	$sites = runQuery("SELECT url from searchable_sites WHERE id IN($sites)", $db, true, false);

	$sites = json_encode($sites);
}


?>




<script src="//code.jquery.com/jquery-latest.min.js"></script>
<script>

<?php

$vars_to_define = array('resultsCache' => '[]','resultsCache_i' => '0',
								'site_start_i' => '0', 'cache_exhausted_right' => 'false',
								'sites' => "'$sites'",'q' => "'$url_q'",
								'resultsOffset' => $resultsOffset);

//var_dump($vars_to_define);
//die;


foreach($vars_to_define as $var => $default) {
	echo "
		if(typeof $var === 'undefined')  {
			$var = [];
		}
		".$var."[$section_num] = $default;
	"; 
}


?>
                
var key = "AIzaSyDBzCfhslTSWG6hVgaZ9eFgVqc1Ck5jxRE";
var cx = "013942562424063258541:ofu8c_sygk4";
                      
var cache_refill_size = 9;
     
var site_num_include = 20; //how many sites to include in the url
var upcomingCache_minSize = 3;  //doesnt need to be section dependent
var base_search_string = "https://www.googleapis.com/customsearch/v1?key="+key+"&cx="+cx+"&num="+cache_refill_size;

function upcomingCache(section_num) {
	return resultsCache[section_num].slice(resultsCache_i[section_num]);
}

function getFormattedResults(num_results, section_num) {
	res = getResultsFromCache(num_results, section_num);

	if(res) {
   	res = formatResults(res);
	}
	else {
   	res = 'Sorry, there was a problem getting your results.';
	}
	return res;
}

//doesnt need to be section dependent
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

	return all_results.join("\n\n");
}


function getResultsFromCache(num_results, section_num) {


	//if there aren't enough results left in the cache
	if(resultsCache_i[section_num]+num_results > resultsCache[section_num].length) {
		console.log("Want "+num_results+", have "+(resultsCache[section_num].length-resultsCache_i[section_num])+"...refilling");

		if(cache_exhausted_right[section_num]) {
      	return [];
		}
		refillCache(false, section_num); //do this syncronously since we need the results now
	}

            

	//we reached the end, there are no more results to populate the cache with
   if(resultsCache[section_num].length < num_results) {
		console.log('No results to return.');
   	return [];
	}

	//if we get here, the cache is successfully populated with enough
	//results that we can return them

	results = resultsCache[section_num].slice(resultsCache_i[section_num], resultsCache_i[section_num] + num_results);
	resultsCache_i[section_num] += num_results; //move cache indexer over


	console.log(upcomingCache(section_num).length+" upcoming in cache");
  
	uc_length = upcomingCache(section_num).length;
	if(uc_length < upcomingCache_minSize) {
		console.log("Pre-emptively refilling since cache only has "+uc_length+" left.");
   	refillCache(true, section_num);
	}   


	return results;
}

function execSearch(url, async, after, section_num) {
//	console.log('ssearching with '+( async ? 'async':'syncronus!'));
	items = [];
	$.ajax({
   	url: url,
		async: async,
		timeout: 2000, //2sec
		complete: function(data) {
			json_obj = data.responseText;


			results='error';
			try {
				results = JSON.parse(json_obj);
			}
			catch(err) {
         	console.log('error');
				console.log(json_obj);
			}

//			console.log('search finished');
//			console.log(results);

			items = [];
			if(results) {
				items = results['items'];
			}
			after(items, section_num);
		}
	});
	return items;
}



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
function getSiteString(section_num) {
	if(!sites) return "";

	subarr = sites[section_num].slice(site_start_i[section_num], site_start_i[section_num]+site_num_include);

	if(subarr.length == 0) return "";
	
	sitestring = subarr.join(' || ');
	sitestring = ' sites('+sitestring+')';

	sitestring = urlencode(sitestring);
	return sitestring;
}

function addItemsToCache(items, section_num) {
	resultsCache[section_num] = resultsCache[section_num].concat(items);
	console.log("Success adding "+items.length+" items to cache. New cache size is "+resultsCache[section_num].length+" ("+(upcomingCache(section_num).length)+" upcoming)"); 
}

function refillCache(async, section_num) {  

	//with current string get more results

	// Code for getting the results from the Google Custom Search Engine

	sitestring = getSiteString(section_num);

	after = function(items, section_num) {
		console.log('in the after function section_num='+section_num);
		console.log(items);
		
		if(!items || items.length < cache_refill_size) {
			console.log('Problem with current search. Resetting and trying again.');
			//move sites filter up
			site_start_i[section_num] += site_num_include;

			//break if no more sites to search through
			if(site_start_i[section_num] >= sites[section_num].length) {
				 console.log("No sites to search through, returning without refill.");
				 cache_exhausted_right[section_num] = true;
				 return;
			}

			resultsOffset[section_num] = 1;

			//call function again to refill with the new vars
			refillCache(async, section_num);
		}
		else {
			resultsOffset[section_num] += cache_refill_size;
			addItemsToCache(items, section_num)
		} 
	};

	execSearch(base_search_string+"&start="+resultsOffset[section_num]+"&q="+q[section_num]+sitestring, async, after, section_num);

	//console.log('new_items:');console.log(new_items);

	//if new results were problematic

	return resultsCache[section_num];
}



</script>
