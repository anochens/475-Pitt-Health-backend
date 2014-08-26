



	<?php

	if($view == 'cartoony') {

	echo "<div class='filtering_wrapper' class='generic_background_section'><div class='generic_header'>Personalize your search</div>";
	                                                         
		echo "<table><form id='filter_form2'>";
 
		$sliderstyle = 'color:rgb(153,153,153); height:20px; width:20px;cursor:pointer';

		foreach($res as $cat) {
			if($cat['is_iama'] == '1') continue;
			$sliderminus = '<span onclick="moveSlider(-1,'.$cat['id'].')" style="font-size:150%;'.$sliderstyle.'" >-</span>';
			$sliderplus = '<span onclick="moveSlider(1,'.$cat['id'].')" style="'.$sliderstyle.'" >+</span>';
									 
			echo "<tr><td class='left'><div>".$cat['name']."</div></td>";;


			echo "<td class='sliderhead'> 
			<table><tr>
				<td>$sliderminus</td>
				<td><div class='slider' id='personalize_".$cat['id']."' name='personalize_".$cat['id']."' ></div></td>
				<td>$sliderplus</td>
			</table></tr>";
			//echo "<td><input type='text' value='100' name='personalize_".$cat['id']."i' /></td></tr>";
			
		}
	}

	else { //view is professional

	echo "<div style='width:800px' class='filtering_wrapper' class='generic_background_section'><div class='generic_header'>Personalize your search</div>";
	                                                         
		echo "<table style='float:left;width:300px'><form id='filter_form2'>";
 

		$count = 0;
		foreach($res as $cat) {
			if($cat['is_iama'] == '1') continue;

			if($count == 4) { //make new table so new column
         	echo "</table><table style='float:right;width:300px;'>";
			}
			$count++;
									 
			echo "<tr><td class='left'><div>".$cat['name']."</div></td>";;


			echo "<td class='checkhead'> 
					<input type='checkbox' id='personalize_".$cat['id']."' name='personalize_".$cat['id']."'>
					</td></tr>";
		}  


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
