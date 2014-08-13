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

function runQuery($q, $db = null, $return = true) {
	if(!$db) {
   	$db = db_connect();
	}
	
   try {
      $results = $db->query($q);
      if($return) {
         return $results->fetchAll(PDO::FETCH_ASSOC);
      }
   }
   catch(PDOException $e) {
      print "Error with query: $q<br>\n";
      print $e->getMessage();
      die;
   }
}


function insert_searchable_sites_from_file($filename) {
	$contents = file_get_contents($filename);


	$data_i = 0;
	$lines = explode("\n", $contents);
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




//UNUSED FOR NOW BELOW THIS POINT

function enter_new_session($param_hitId, $param_workerId) {
   $db = db_connect();

   if(has_finished_by_ip() || 
		(has_finished_by_mturk_id($param_workerId) && $param_workerId !='test_mturk_id'))
		return 0;



   $sql = 'INSERT INTO session(ip, param_hitId, param_workerId, treatment_id) VALUES(?,?,?,?)';

   $prep = $db->prepare($sql);
   $prep->execute(array(get_ip(),$param_hitId, $param_workerId, get_new_treatment()));

   $sid = $db->lastInsertId();
   setcookie('sid', $sid, time()+60*60*24*30, '/');
   return $sid;
}

function edit_session($data, $ignoreOtherData, $prepost) {
	$db = db_connect();
	$fields=array();

	if(array_key_exists('pre_mturk_id',$data)) {
		$fields []= 'pre_mturk_id';
	}

	if(array_key_exists($prepost.'_email',$data)) {
		$fields []= $prepost.'_email';
	}              

	if(!$ignoreOtherData) {
		$data[$prepost.'_info'] = json_encode($data); 
		$fields[] = $prepost.'_info';
	}


	if(array_key_exists('email_sent',$data)) {
		$fields []= 'email_sent';
	}                   

	if($prepost == 'override') {
   	$fields = array_keys($data);
	}


	$fields_str = array_reduce($fields, function($arr, $v) {
		if(!$arr) $arr = array();
      $arr []= $v."=:".$v;
		return $arr;
	});
	$fields_str = implode(',',$fields_str);


	$sid = intval($_COOKIE['sid']);
	$sql = "UPDATE session SET $fields_str WHERE id=:sid";

   $prep = $db->prepare($sql);

	$prep->bindParam(':sid', $sid);

	foreach($fields as $key) {
   	$prep->bindParam(':'.$key, $data[$key]);
	}

	$prep->execute();
}

function get_from_session($sid, $field, $from_pre_info = false) {
	$db = db_connect();

	$sid = intval($sid);

   $subfield = $field;
	if($from_pre_info) {
		$field = 'pre_info';
	}

	$sql = "SELECT $field FROM session WHERE session.id = $sid";

   $data = runQuery($db, $sql, true);
	if(!$data || count($data) < 0) return false;


	$fieldval = $data[0][$field];


   if($from_pre_info) {
		$pre_info = json_decode($fieldval, true);

		if(!$pre_info || !array_key_exists($subfield, $pre_info)) return false;
		$fieldval = $pre_info[$subfield];
	}

	return $fieldval;
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

function curr_session_is_valid() {
	if(!array_key_exists('sid', $_COOKIE)) return false;
	$db = db_connect();

	$sid = intval($_COOKIE['sid']);

	$sql = "SELECT count(*) as num from session where session.id=$sid";
   $data= runQuery($db, $sql, true);

	$count = $data[0]['num'];

	if($count == 0) {
		setcookie('sid', $sid, time()-1000, '/');
		unset($_COOKIE['sid']);
		return false;
	}

	if($count > 1) die('error');

	return true;


}


function recordEvent($session_id, $page_name, $subject_name, $event_name, $current_time, $current_time_ms) {
	$db = db_connect();

	$sql = 'INSERT INTO page_event(session_id, page_name, subject_name, event_name, ts, ts_ms) VALUES(?,?,?,?, ?, ?)';

   $prep = $db->prepare($sql);
	$prep->execute(array($session_id, $page_name, $subject_name, $event_name, $current_time, $current_time_ms)); 
}

