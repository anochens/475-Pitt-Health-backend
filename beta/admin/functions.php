<?php
//modified version of https://github.com/anochens/melodiesforus/blob/master/functions.php

//These functions are included on pretty much every page

include_once('config.php');

function db_connect() {
	global $db;
	if($db) return $db; //if a db connection exists, don't get another one

	try {
		$db = new PDO('mysql:host='.DB_LOCATION.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
		print $e->getMessage();
	}
	return $db;
}

function get_ip() {
	return $_SERVER['REMOTE_ADDR'];
}

function runQuery($q, $db = null, $return = true, $assoc = true) {
	if(!$db) {
   	$db = db_connect();
	}
	
   try {
      $results = $db->query($q);
      if($return) {

			if($assoc) {
				return $results->fetchAll(PDO::FETCH_ASSOC);
			}
			else {
				return $results->fetchAll(PDO::FETCH_COLUMN);
			}
				
      }
   }
   catch(PDOException $e) {
      print "Error with query: $q<br>\n";
      print $e->getMessage();
      die;
   }
}


function insert_searchable_sites_from_file($filename, $lines_from_file = null) {
	if($filename) {
		$contents = file_get_contents($filename);

		$data_i = 0;
		$lines = explode("\n", $contents); 
	}
	else {
   	$lines = $lines_from_file;
	}


   $header = $lines[0];

	$cols = explode(',',$header);

	//handle null cols
	for($i=0;$i<count($cols);$i++) {
		trim($cols[$i]);
		if($cols[$i] == '') {
			$cols[$i] = "field_$i";
		}
	}

	$data = array();

//work w each line
	for($data_i=1;$data_i<count($lines);$data_i++) {
		$line = $lines[$data_i];

		$rowdata = array();
      $fields = explode(',',$line);

		
		//put quotes around sites
		$rowstr = '"'.implode('","',array_map('urlencode', $fields)).'"';
		
		//add fields if they are not all specified at the end
		$n_fields_to_add = count($cols)-count($fields);
		if($n_fields_to_add > 0) {
      	$rowstr .= str_repeat(',""', $n_fields_to_add);
		}

		$data[$data_i] = "($rowstr)";
	}

	$colstr = implode(',', $cols);
	$valstr = implode(',', $data);

	$q="INSERT INTO searchable_sites ($colstr) VALUES".$valstr;

	runQuery($q, null, false);
}



function redir($page, $includeQuery = false) {
	$base = basename($_SERVER["SCRIPT_FILENAME"]);
	if($page == $base || $page == "/$base") {
   	return; //don't redirect if we are already on the page
	}
	if($includeQuery) {
		if($_SERVER['QUERY_STRING']) {
			$page .= "?".$_SERVER['QUERY_STRING'];
		}
	}
	header("Location: $page");

	die;
}   


//cse annotations and context related functions
                                              
function generalizeURL($url) {
	$url = str_replace("http://","", $url);
	$url = str_replace("https://","", $url);
	if(substr($url, -1) == '/') {
   	$url .= "*";
	} 
	$url = str_replace("www.","*.", $url);
	return $url;
}


//sorts an array of arrays, first by size, then by first elem
function sortfunc($a, $b) {
	$count_a = count($a);
	$count_b = count($b);

	if($count_a != $count_b) {
   	return $count_a - $count_b;
	}

	if($count_a == 1) {
   	return $a[0]-$b[0];
	}
	return 0;
}

//from http://docstore.mik.ua/orelly/webprog/pcook/ch04_25.htm
//modified for better sorting order
function pc_array_power_set($array) {
	// initialize by adding the empty set
	$results = array(array( ));

	foreach ($array as $element) {
		foreach ($results as $combination) {
			$new_elem = array_merge(array($element), $combination);
			sort($new_elem);
			array_push($results, $new_elem );
		}
	}

	usort($results, "sortfunc");

	return $results;
}
 


