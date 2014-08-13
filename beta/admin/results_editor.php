<?php

//this file will output all info associated with cateorgizing results
//and will do processing of changes

include('functions.php');

$db = db_connect();

//insert_searchable_sites_from_file('sitestosearch.txt');

$cats = runQuery('SELECT * FROM search_categories');

$sites = runQuery('SELECT * FROM searchable_sites');

?>

<table border=2>

<tr>
<?php

$catcount = count($cats);

echo "<th>Site</th>";
foreach($cats as $cat) {
	echo "<th>".$cat['name'].'</th>';
}

echo '</tr>';

foreach($sites as $site) {
	echo '<tr>';
	echo '<th style="text-align:right">';
	if($site['name']) {
   	echo urldecode($site['name']);
	}
	else {
   	echo urldecode($site['url']);
	}
   echo "</th>";


	for($i=0;$i<$catcount;$i++) {
   	echo "<td></td>";
	}
	echo "</tr>";
}

echo "</table>";

