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

function convert2($string) {
	$string = strtolower($string);
	$string = str_replace(array(' ','/'), "_", $string);

	return $string;
}

foreach($sites as $site) {
	$r_cats = explode(',', $site['categories']);
   if(count($r_cats) == 0) continue;

	$result = "\n<Annotation about='".$site['url']."'>\n";

	$num_added = 0;
	$result .= "\t<label name='default' />\n";

	foreach($r_cats as $cat) {
		if(!$cat || $cat == '') continue;

//		if($cats[$cat]['is_iama'] == 1) {
			$result .= "\t<label name='".convert2($cats[$cat]['name'])."' />\n";
			$num_added++;
//		}
	}
	
	
	$result .= "</Annotation>\n";

	if($num_added > 0)
		$results .= $result;	
}
       
$results = $prelim . $results . $post;

echo $results;
