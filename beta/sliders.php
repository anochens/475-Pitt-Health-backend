





<div class='filtering_wrapper' class='generic_background_section'>
	<div class='generic_header'>Personalize your search</div>
	<table>
	<form id='filter_form2'>
	 
	<?php

	$imgsrc = 'height:20px; width:20px';

	foreach($res as $cat) {
		if($cat['is_iama'] == '1') continue;
		$sliderminus = '<img onclick="moveSlider(-1,'.$cat['id'].')" style="'.$imgsrc.'" src="img/sliderminus.png" />';
		$sliderplus = '<img onclick="moveSlider(1,'.$cat['id'].')" style="'.$imgsrc.'" src="img/sliderplus.png" />';
                         
		echo "<tr><td class='left'><div style='width:100px'>".$cat['name']."</div></td>";;


		echo "<td class='sliderhead'> 
		<table><tr>
			<td>$sliderminus</td>
			<td><div class='slider' id='personalize_".$cat['id']."' name='personalize_".$cat['id']."' ></div></td>
			<td>$sliderplus</td>
		</table></tr>";
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
