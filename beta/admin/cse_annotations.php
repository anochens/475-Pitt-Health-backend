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


foreach($sites as $site) {
	$r_cats = explode(',', $site['categories']);
   if(count($r_cats) == 0) continue;

	$result = "\n<Annotation about='".generalizeURL($site['url'])."'>\n";

	$num_added = 0; //need to keep track 
						 //bc google doesnt like a annotation without any labels
	$result .= "\t<label name='default' />\n";

	$iama_list = array();
	foreach($r_cats as $iama){
		if($iama && $cats[$iama] && $cats[$iama]['is_iama'] == 1) {
			$iama_list[] = $cats[$iama]['id'];
		}
	}
	$iama_list = pc_array_power_set($iama_list);

	foreach($iama_list as $iama) {
		if(!$iama) continue;

		$iama = implode('_',$iama);

		foreach($r_cats as $cat) {
			if(!$cat || $cat == '') continue;

			if($cats[$cat]['is_iama'] == 0) {
				$result .= "\t<label name='iama_".$iama."__filter_".$cats[$cat]['id']."' />\n";
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
