<?php

	// This function will be responsible for executing the search and returning
	// a useable result of the search
	function execute_search($url) {
		$json_obj = file_get_contents($url);

		$start = 'json(';
		$end = ');';

		$substr_start_pos = strpos($json_obj, $start) + strlen($start);
		$substr_length = strlen($json_obj) - $substr_start_pos - (strlen($json_obj) - strpos($json_obj, $end));

		$json_obj = substr($json_obj, $substr_start_pos, $substr_length);

		$results = json_decode($json_obj, true);
		return $results;
	}

?>