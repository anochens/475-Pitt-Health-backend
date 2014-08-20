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

		extras = [];

		//empty checkboxes don't submit by default, so fix this
		checkboxes = $('input:checkbox');
		for(i=0;i<checkboxes.length;i++) {
			checkboxes[i] = $(checkboxes[i]);
			extras += "&"+checkboxes[i].attr('name') +"="+ checkboxes[i].val();
		}

		sliders = $('.slider');

		for(i=0;i<sliders.length;i++) {
			sliders[i] = $(sliders[i]);
			value = sliders[i].slider("value");
			console.log(value);
			console.log('---------');
			extras += "&"+sliders[i].attr('name') +"="+ value;
		}        
		

		formvars = $('form').serialize(); //get data from all forms on the page at once

		window.location = 'results.php?submit=submit&'+formvars+extras;

		return false;
	});



	
	$('input:checkbox').change(function() {
		val = $(this).val();

		if(val == '1') {
			$(this).val('0');
		}
		else {
      	$(this).val('1');
		}
	});

});

function toggleAdvanced() {
   $('.filtering_wrapper').toggle(600);

	if($('#toggler_link').html() == 'Simple search') {
   	$('#toggler_link').html('Advanced search');
		$('#advanced_search_indicator').val(0);
	}
	else {
   	$('#toggler_link').html('Simple search');
		$('#advanced_search_indicator').val(1);
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
	<input id='advanced_search_indicator' name='advanced_search_indicator' value='1' type='hidden'>
	<tr><th colspan=2 class='generic_header'>Who are you?</th></tr>
	 
	<?php

	foreach($res as $cat) {
		if($cat['is_iama'] == '0') continue;

		echo "<tr><td class='left'>".$cat['name']."</td>";;
		echo "<td><input checked type='checkbox' value='1' name='IAmA_".$cat['id']."i' /></td></tr>";
		
	}

	?>
	</form>
	</table>
</div>

<br>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
  


  <script>
  $(function() {
    $(".slider").slider({
      value:50,
      min: 0,
      max: 100,
      step: 40,
		animate:'fast',
      slide: function(event, ui) {
        $(this).val('$' + ui.value);
      }
    });
    //$(this).val($(this).slider("value"));
  });
  </script>


<style>
.ui-slider .ui-slider-handle { height:.5em; width:.5em;margin-top:3px; border-radius:30px; }
.ui-slider { background: rgb(235, 235, 235); height:5px }
.slider { width:150px; margin-right:30px}
.filtering_wrapper tr { padding-bottom:5px}
.ui-widget-content .ui-state-default { background:rgb(241, 0, 0) }

</style>

<div class='filtering_wrapper' class='generic_background_section'>
	<table>
	<form id='filter_form2'>
	<tr><th colspan=2 class='generic_header'>Personalize your search</th></tr>
	 
	<?php

	foreach($res as $cat) {
		if($cat['is_iama'] == '1') continue;

		echo "<tr><td class='left'>".$cat['name']."</td>";;


		echo "<td><div class='slider ui-corner-all' name='personalize_".$cat['id']."' ></div></td></tr>";
		//echo "<td><input type='text' value='100' name='personalize_".$cat['id']."i' /></td></tr>";
		
	}

	?>




	<tr>

		<td colspan='2' style='text-align:right;padding-top:10px'>
			Videos <input value='1' type='checkbox' name='want_videos' />
			Images <input value='1' type='checkbox' name='want_images' />
		</td>

	</form>

	</table>   


</div>
