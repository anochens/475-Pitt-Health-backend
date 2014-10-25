<?php
checkAdultStatus();
global $view;

if(!isset($sidebar)) $sidebar = false;

include_once('admin/functions.php');

$db = db_connect();

$q='SELECT * from search_categories';
$res = runQuery($q, $db);

?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
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

		<?php //if($view == 'cartoony') {  ?>
		sliders = $('.slider');

		for(i=0;i<sliders.length;i++) {
			sliders[i] = $(sliders[i]);
			value = sliders[i].slider("value");
			extras += "&"+sliders[i].attr('name') +"="+ value;
		}        
		<?php //} ?>
		

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
	<?php if(!$sidebar) { ?>
	<a onclick='toggleAdvanced();' id='toggler_link'>Advanced search</a>
	<?php } ?>
</div>

<br/>

<?php if(!$sidebar) { ?>


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
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
  


  <script>
  $(function() {
	 <?php

 //   if($view == 'cartoony') { ?>
    $(".slider").slider({
      value:60,
      min: 0,
      max: 100,
      step: 10,
		animate:'fast',
      slider: function(event, ui) {
		  //if(ui.value < 30) ui.value=30;
      },
		stop: function(event, ui) {
			if(!ui || !ui.value) { return; }
			//console.log(ui);
			//console.log(ui.value);

			elem = ui;

			out = $(elem.handle).parent();
			id = out.attr('id').replace('personalize_','');
			target = $('#personalize_'+id);
			
			                          /*
			console.log('checking for <50');
			if(target.val()<50 || target.slider('value') < 50) {
				console.log('setting 50');
				ui.value=50;
				target.val(50);
				target.slider('value',50);
				ui.stop();
				event.stopPropogation();
			}                           */
			
			
			
			
			result_section = $("#results"+id);

			submain = $('#results'+id+' #sub_main_wrapper_results');
			val = elem.value;
			if(!val) val = target.val();

			if(val < 50) {
				//hide section if shown
				console.log(out.attr('id')+"="+elem.value+"-> hiding");

				if(result_section) {
            	result_section.hide();
				}

			}
			else {
				<?php if($sidebar) { ?> 
				//if section not on page, load it
				//and make sure hidden section is shown
				console.log('wanting to show '+id);
				isubmain.length > 0) {
					console.log('just showing it');
            	result_section.show();
				}
				else {
            	url = $('#results'+id+'_url').html();
					if(url) {
						url = (url+'').replace('&amp;','&');
						url = (url+'').replace('&amp;','&');
						url = (url+'');
						console.log('trying to load from '+url);
						result_section.load(url);	
					}
					else {
               	console.log('no url to load from...');
					}
				}
				<?php } ?>
			}

		}
    });

    <?php

	/* }
	 else { //view is pro, so do pro things

	 }*/
	 ?>

  });

  </script>



<style>
.ui-slider .ui-slider-handle { height:.5em; width:.5em;margin-top:3px; border-radius:30px; }
.ui-slider { background: rgb(235, 235, 235); height:5px }
.slider { width:150px;}
.filtering_wrapper tr { padding-bottom:5px}
.ui-widget-content .ui-state-default { background: rgb(171,5,26); }
</style>

<?php //if($view == 'cartoony') { ?>
<script>
	function moveSlider(forward, cat_id) {
		target = $('#personalize_'+cat_id);
		newVal = forward*40+target.val();

		if(newVal<10)  {newVal=10; }
		if(newVal>100) {newVal=100; }

		target.val(newVal);
		target.slider('value', newVal);
	}

</script>

<?php

	include_once('sliders.php');
//} 


if($sidebar) { 
	echo "<style>.filtering_wrapper { display:block;}</style>";

	//fill sliders with values from $_GET
		$pattern = '/^(personalize)_(\d+)$/';
		echo "
		<script>
			$(document).ready(function() {\n ";
      
		foreach($_REQUEST as $k => $v) {
			if(!preg_match($pattern, $k, $captures)) {
				continue; //skip bad lines
			}
			$passedval = intval($captures[2]);

			//if($view == 'cartoony') {
				echo "$('#personalize_$passedval').slider('value', '$v');";
			/*}
			else {
				if($v == 'on') {
					echo "$('#personalize_$passedval').prop('checked', true);\n";
				}
			} */

		}
                    
			echo "});
			</script>";

}

