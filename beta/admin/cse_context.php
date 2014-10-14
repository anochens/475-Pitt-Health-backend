<?php
  header('Content-Type: application/xml; charset=utf-8');

include_once('functions.php');

$db = db_connect();

$cats = runQuery('SELECT * FROM search_categories');
$q = 'SELECT * FROM searchable_sites';
$sites = runQuery($q, $db);

$cats_to_sites = array();

foreach($sites as $site) {
	$ecats = explode(',', $site['categories']);

	foreach($ecats as $cat) {
		if(!$cat) continue;
		if(!array_key_exists($cat, $cats_to_sites)) {
			$cats_to_sites[$cat] = array();
		}
		$cats_to_sites[$cat][] = $site['url'];
	}
}
 
$temp = array();
foreach($cats as $cat) {
	$temp[$cat['id']] = $cat;
}
$cats = $temp;

$title = 'Do not care';

$prelim = "<CustomSearchEngine>\n\t<Title>$title</Title><Context><Facet>\n";
$post = "</Facet></Context></CustomSearchEngine>\n";


$results = '';



function convert($string) {
	$string = strtolower($string);
	$string = str_replace(array(' ','/'), "_", $string);

	return $string;
}                                                                 

foreach($cats as $cat) {
	if($cat['is_iama'] == 1) continue;
	$result = '';
	$result .= "\t<FacetItem title='".$cat['name']."'>\n";
	$result .= "\t\t<Label name='".convert($cat['name'])."' mode='FILTER' enable_for_facet_search='true' label_onebox_boost='0'>\n\t\t\t<Rewrite></Rewrite>\n\t\t</Label>\n";
/*
	foreach($cats_to_sites[$cat['id']] as $site) {
		$result .= "\t\t<Label name='$site' mode='FILTER' />\n";
	}
*/

	$result .= "\t</FacetItem>\n\n";
	$results .= $result;	
}
       
$results = $prelim . $results . $post;

echo $results;
