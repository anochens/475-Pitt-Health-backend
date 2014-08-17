<?php

	// This function will be responsible for executing the search and returning
	// a useable result of the search
	function execute_search($url) {
		// gets the json object from google
		$json_obj = file_get_contents($url);

		//var_dump($json_obj);

		// keep track of the little tidbits that google adds to their json object
		$start = 'json(';
		$end = ');';

		// find the start and end of the json object
		$substr_start_pos = strpos($json_obj, $start) + strlen($start);
		$substr_length = strlen($json_obj) - $substr_start_pos - (strlen($json_obj) - strpos($json_obj, $end));

		// get the json object without the tidbits added from google
		$json_obj = substr($json_obj, $substr_start_pos, $substr_length);

		// json decode the json object into an array like structure 
		$results = json_decode($json_obj, true);

		// return the json object that is now an array of many arrays
		return $results;
	}

?>
