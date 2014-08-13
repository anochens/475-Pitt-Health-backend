<?php

//this file will output all info associated with cateorgizing results
//and will do processing of changes

include('functions.php');

$db = db_connect();

//this would insert sites into the database from a file
//insert_searchable_sites_from_file('sitestosearch.txt');

$cats = runQuery('SELECT * FROM search_categories');
$sites = runQuery('SELECT * FROM searchable_sites');

	var_dump($_REQUEST);

if(array_key_exists('changes',$_POST)) {
	var_dump($_POST);
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

