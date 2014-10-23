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

$title = 'My CSE';

$prelim = "<CustomSearchEngine>\n\t<Title>$title</Title><Context><Facet>\n";
$post = "</Facet></Context></CustomSearchEngine>\n";


$results = '';


$results .= createFacetItem();

                                                              


$iama_list = array();
foreach($cats as $id=>$iama){
	if($iama  && $iama['is_iama'] == 1) {
		$iama_list[] = $iama['id'];
	}
}


$iama_list = pc_array_power_set($iama_list);

foreach($iama_list as $iama) {
	if(!$iama) continue;

	$iama = implode("_", $iama);
    
	foreach($cats as $cat) {
		if($cat['is_iama'] == 1) continue;

		$name = "iama_".$iama."__filter_".$cat['id'];
		$results .= createFacetItem($name, $name);
	}
}
       
$results = $prelim . $results . $post;

echo $results;


function createFacetItem($name = "default") {
	$result = "";
	$result .= "\t<FacetItem title='$name'>\n";
	$result .= "\t\t<Label name='$name' mode='FILTER' enable_for_facet_search='true' label_onebox_boost='0'>\n";
	$result .= "\t\t\t<Rewrite></Rewrite>\n";
	$result .=" \t\t</Label>\n";

	$result .= "\t</FacetItem>\n\n"; 	

	return $result;
}
