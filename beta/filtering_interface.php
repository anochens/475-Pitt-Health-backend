<?php
checkAdultStatus();
global $view;
                  

include_once('admin/functions.php');

$db = db_connect();

$q='SELECT * from search_categories';
$res = runQuery($q, $db);
     
?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-latest.min.js"></script>
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
	<?php if(!isset($sidebar) || !$sidebar) { ?>
	<a onclick='toggleAdvanced();' id='toggler_link'>Advanced search</a>
	<?php } ?>
</div>

<br/>

<?php if(!isset($sidebar) || !$sidebar) { ?>


<div class='filtering_wrapper' class='generic_background_section'>
	<div class='generic_header'>Who are you?</div>
	<table>
	<form id='filter_form1'>
	<input id='advanced_search_indicator' name='advanced_search_indicator' value='0' type='hidden'>
	 
	<?php

	foreach($res as $cat) {
		if($cat['is_iama'] == '0') continue;

		echo "<tr><td class='left'>".$cat['name']."</td>";;
		echo "<td style='padding-right:30px'><input class='checkbox' type='checkbox' value='0' name='IAmA_".$cat['id']."' /></td></tr>";
		
	}

	?>
	</form>
	</table>
</div>

<?php } ?>

<br>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-latest.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
  


  <script>
  $(function() {
    $(".slider").slider({
      value:60,
      min: 0,
      max: 100,
      step: 10,
		animate:'fast',
      slider: function(event, ui) {
		  if(ui.value < 30) ui.value=30;
      }
    });
    //$(this).val($(this).slider("value"));
  });
  </script>


<style>
.ui-slider .ui-slider-handle { height:.5em; width:.5em;margin-top:3px; border-radius:30px; }
.ui-slider { background: rgb(235, 235, 235); height:5px }
.slider { width:150px;}
.filtering_wrapper tr { padding-bottom:5px}
.ui-widget-content .ui-state-default { background: rgb(171,5,26); }
.sliderhead{ } 

</style>

<script>
function moveSlider(forward, cat_id) {
	target = $('#personalize_'+cat_id);
   newVal = forward*40+target.slider("value");

	if(newVal<50) newVal=50;
	if(newVal>90) newVal=90;

	target.slider('value', newVal);
}

</script>

<?php

include('sliders.php');


if(isset($sidebar) && $sidebar) { 
	echo "<style>.filtering_wrapper { display:block;width:100% }</style>";
}
?>
 

