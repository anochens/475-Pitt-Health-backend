<?php


include_once('admin/functions.php');

$db = db_connect();

$q='SELECT * from search_categories';
$res = runQuery($q, $db);
     
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
                                     
<script>
$(document).ready(function() {
	$('form').submit(function(event) {
		formvars = $('form').serialize(); //get data from all forms on the page at once

		window.location = 'results.php?submit=submit&'+formvars;

		return false;
	});
});

function toggleAdvanced() {
   $('.filtering_wrapper').toggle(600);

	if($('#toggler_link').html() == 'Simple search') {
   	$('#toggler_link').html('Advanced search');
	}
	else {
   	$('#toggler_link').html('Simple search');
	}
}

</script>

<div id='toggler'>
	<a onclick='toggleAdvanced();' id='toggler_link'>Simple search</a>
</div>

<br/>

<div class='filtering_wrapper' class='generic_background_section'>
	<table>
	<form id='filter_form1'>
	<tr><th colspan=2 class='generic_header'>Who are you?</th></tr>
	 
	<?php

	foreach($res as $cat) {
		if($cat['is_iama'] == '0') continue;

		echo "<tr><td class='left'>".$cat['name']."</td>";;
		echo "<td><input type='checkbox' name='IAmA_".$cat['id']."i' /></td></tr>";
		
	}

	?>
	</form>
	</table>
</div>

<br>

<div class='filtering_wrapper' class='generic_background_section'>
	<table>
	<form id='filter_form2'>
	<tr><th colspan=2 class='generic_header'>Personalize your search</th></tr>
	 
	<?php

	foreach($res as $cat) {
		if($cat['is_iama'] == '1') continue;

		echo "<tr><td style='width:50%'>".$cat['name']."</td>";;

		//switch out for slider eventually
		echo "<td><input type='text' value='100' name='personalize_".$cat['id']."i' /></td></tr>";
		
	}

	?>


	<tr>
		<td></td>

		<td>
			Videos <input type='checkbox' name='want_videos' />
			Images <input type='checkbox' name='want_images' />
		</td>

	</form>

	</table>   


</div>
