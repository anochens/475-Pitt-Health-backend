<?php
  header('Content-Type: application/xml; charset=utf-8');

include_once('functions.php');

$db = db_connect();

$cats = runQuery('SELECT * FROM search_categories');
$sites = runQuery('SELECT * FROM searchable_sites');

$temp = array();
foreach($cats as $cat) {
	$temp[$cat['id']] = $cat;
}
$cats = $temp;


$prelim = "<Annotations>\n";
$post = "</Annotations>\n";


$results = '';

function striptitle($string) {
	$string = strtolower($string);
	$string = str_replace(array(' ','/'), "_", $string);

	return $string;
}

function generalizeURL($url) {
	$url = str_replace("http://","", $url);
	$url = str_replace("https://","", $url);
	if(substr($url, -1) == '/') {
   	$url .= "*";
	} 
	$url = str_replace("www.","*.", $url);
	return $url;
}

foreach($sites as $site) {
	$r_cats = explode(',', $site['categories']);
   if(count($r_cats) == 0) continue;

	$result = "\n<Annotation about='".generalizeURL($site['url'])."'>\n";

	$num_added = 0;
	$result .= "\t<label name='default' />\n";

	$iama_list = array();
	foreach($r_cats as $iama){
		if($cats && $iama && $cats[$iama] && $cats[$iama]['is_iama'] == 1) {
			$iama_list[] = striptitle($cats[$iama]['name']);
		}
	}

	foreach($iama_list as $iama) {

		foreach($r_cats as $cat) {
			if(!$cat || $cat == '') continue;

			if($cats[$cat]['is_iama'] == 0) {
				$result .= "\t<label name='".$iama."__".striptitle($cats[$cat]['name'])."' />\n";
				$num_added++;
			}
		}
	}
	
	
	$result .= "</Annotation>\n";

	if($num_added > 0)
		$results .= $result;	
}
       
$results = $prelim . $results . $post;

echo $results;
