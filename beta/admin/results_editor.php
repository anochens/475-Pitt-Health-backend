<?php

//this file will output all info associated with cateorgizing results
//and will do processing of changes

include('functions.php');

$db = db_connect();

if(count($_REQUEST) > 0) {
	handleChanges($_REQUEST);
}   
//this would insert sites into the database from a file
//insert_searchable_sites_from_file('sitestosearch.txt');

$cats = runQuery('SELECT * FROM search_categories');
$sites = runQuery('SELECT * FROM searchable_sites');

	


function handleChanges($changes) {
   $pattern = '/^edit_cat(\d+)_site(\d+)$/';

	foreach($changes as $change => $v) {
   	if(!preg_match($pattern, $change, $captures)) {
			echo "skipped $change<br>";
			continue; //skip bad lines
		}

		//echo "Change OK: $change=$v<br>";
      $cat_no = $captures[1];
		$site_no = $captures[2];

		makeDBchange($site_no, $cat_no, $v);
	}
}

function makeDBchange($site_no, $cat_no, $v) {
	$db = db_connect();

	//find current categories
	$sql = 'SELECT categories from searchable_sites WHERE id='.$site_no;
	$res = runQuery($sql);

	$categories = explode(',', $res[0]['categories']);

	//removes category
	if($v == 0) {
		$index = array_search($cat_no, $categories);
   	if($index !== FALSE) { //we have found it
			unset($categories[$index]);
		}
	}
	else if($v == 1) { //adds category
		if(!in_array($cat_no, $categories)) {
			$categories[] = $cat_no;
		}
	}
	else {
		die('Error');
	}

	$categories = implode(',', $categories);


	//make update to the categories
	$sql = 'UPDATE searchable_sites SET categories=:categories WHERE id=:id';

   $prep = $db->prepare($sql);
	$prep->bindParam(':categories', $categories);
	$prep->bindParam(':id', $site_no);

	$prep->execute(); 

}


?>


<style>
#main_wrapper { overflow:scroll; height: 1400px; } 
td { min-width:75px; }
.cell {
	width:45px;
	margin-left:auto;
	margin-right:auto;
	font-size:larger;
	text-align:center;
	padding:10px;
}

</style>

<script type="text/javascript" src="//code.jquery.com/jquery-latest.min.js"></script>

<script>
$(function() {
	
	$("button").click(btnHandler);
	$('form').submit(function(event) {
		$(window).unbind("beforeunload");
	});
	
});

function checkUnload() {
	$(window).bind('beforeunload', function(){
		return '>>>>>You have unsaved changes<<<<<<<< \n If you leave the page now, they will not be saved.';
	});
}

function btnHandler() {
	txt = $(this).html();

	tparent = $(this).parent();
	parentId = tparent.attr('id');

	if(!parentId) {
		return;
	}

	

	//use a hidden field to keep track of edits
	hiddenElem = '<input type="hidden" id="edit_'+parentId+'"  name="edit_'+parentId+'" value=\'';
	if(txt == 'Select') {
		tparent.html( $('#sampleCheckMark').html() );			
		hiddenElem += '1';
	}
	else {
		tparent.html( $('#sampleXMark').html() );			
		hiddenElem += '0';
	}
	hiddenElem += "' ></input>";

	if($('#edit_'+parentId).length) {
		$('#edit_'+parentId).remove();
	}

	$('form').append(hiddenElem);

	checkUnload();
	$("button").click(btnHandler);
}


</script>

<span name='sampleCheckMark'  id='sampleCheckMark' style='visibility:hidden'>
	<span style="color:#008A00">
		&#x2713;
	</span>
	<br>
	<button>Unselect</button>
</span>


<span name='sampleXMark' id='sampleXMark' style='visibility:hidden'>
	<span style="color:#DC381F;">
		&#x2716;
	</span>
	<br>
	<button>Select</button>
</span>
 
<table border=2>




<form action='.' method='POST' id='changes' name='changes'>


<tr><td colspan=3 align='right' style='border:none'>
<input type='submit' value='Save changes and reload'>
</td></tr>




<tr>
<?php

$catcount = count($cats);

echo "<th>Site</th>";
foreach($cats as $cat) {
	echo "<th>".$cat['name'].'</th>';
}

echo '</tr>';
?>                              


<?php

foreach($sites as $site) {

   $site_cats = explode(',',$site['categories']);

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
   	echo "<td class='cell'>"; 

		echo "<span id='cat".$cats[$i]['id'].'_'.'site'.$site['id']."'>"; 
		if(in_array($cats[$i]['id'], $site_cats)) {
			?>

			<span style="color:#008A00">
				&#x2713;
			</span>
			<br>
			<button>Unselect</button>

			<?php
		}
		else {
		?> 

			<span style="color:#DC381F;">
				&#x2716;
			</span>
			<br>
			<button>Select</button>

		<?php
		}
		
		echo "</span></td>";
	}
	echo "</tr>";
}

echo "</form></table>";

