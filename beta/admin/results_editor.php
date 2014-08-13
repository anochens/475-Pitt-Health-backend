<?php

//this file will output all info associated with cateorgizing results
//and will do processing of changes

include('functions.php');

$db = db_connect();

insert_searchable_sites_from_file('sitestosearch.txt');


$res = runQuery('SELECT * FROM searchable_sites');
var_dump($res);
