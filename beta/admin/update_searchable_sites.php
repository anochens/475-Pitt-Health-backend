<?php

include_once('functions.php');

function handleData($data) {
	$lines = read_csv_data();

	$lines = substitute_db_ids_into_lines($lines);

	make_db_updates($lines, $data['overwrite']);
   print "Data inserted successfully.<br>\n";

	return $lines;
}
 
function read_csv_data() {
	$csv = array();

	// check there are no errors
	if($_FILES['file']['error']){
   	return;
	}

	// necessary if a large csv file
	//set_time_limit(0);

	$filePath = $_FILES['file']['tmp_name'];
	$csv_content = fopen($filePath, 'r');
	$is_first = true;

	while (!feof($csv_content)) {
		$line = fgetcsv($csv_content,1000);

		if(!$line[0]) { //exclude blank websites
			continue;
		}

		$line = fix_line($line, $is_first);

		if($is_first) {
			$is_first = false;
		}

		$lines[] = $line;

	}
	fclose($csv_content);

	return $lines;
}



function fix_line($line, $is_first = false) {
	if($line[1] == '1--3') $line[1] = '1,2,3';
	if(!$is_first) {
		$line[1] = str_replace(' ', '', $line[1]);
		$line[2] = str_replace(' ', '', $line[2]);
		$line[1] = $line[1].','.$line[2];
	}
	else {
		$line[1] = 'Categories';

	}
	unset($line[2]);

	return $line;
}

function substitute_db_ids_into_lines($lines)  {
	$db = db_connect();

	$q = 'SELECT id, csv_id FROM search_categories';
	$categories = runQuery($q);

	$csv_to_db = array();

	foreach($categories as $category) {
		$csv_to_db[$category['csv_id']] = $category['id'];
	}


	for($i=1;$i<count($lines);$i++) {
		$csv_cats = explode(',', $lines[$i][1]);
		$db_cats = array();

		foreach($csv_cats as $csv_cat) {
			if(!$csv_cat) continue;
			$db_cats[] = $csv_to_db[$csv_cat];
		}
		$db_cats = implode(',', $db_cats);

		$lines[$i][1] = $db_cats;
	}
	return $lines;
}

//this will add duplicates!
function make_db_updates($lines, $overwrite) {

	$db = db_connect();
	
	if($overwrite == '1') {
		runQuery("DELETE FROM searchable_sites;", $db, false);
		print "Old data cleared out.<br>\n";
	}
	foreach($lines as $line) {
		$sql = 'INSERT INTO searchable_sites(url, categories) VALUES(:url, :categories) ';

		$prep = $db->prepare($sql);
		$prep->bindParam(':url', $line[0]);
		$prep->bindParam(':categories', $line[1]);

		$prep->execute(); 
	}
}

if(array_key_exists('submit_csv', $_POST)) {
	handleData($_POST);
}








?>


<form action='.' method='POST' enctype='multipart/form-data'>

<label for='file'>Select CSV file:</label>
<input type='file' name='file' id='file'> </input>

<label for='overwrite'>Replace current sites?</label>
<select id='overwrite' name='overwrite'>
	<option value='1'>Yes</option>                   
	<option value='0'>No</option>
</select>

<input type='submit' name='submit_csv' id='submit_csv' value='Upload'>


</form>
