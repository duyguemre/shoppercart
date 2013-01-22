			<div id="<?php echo $section?>">
  				<?php 
  						if(isset($modules)) {
	  						for ($row=0;$row<count($modules);$row++) {
	  							echo  '<div id="row">';		  							
		  						for ($column=0;$column<count($modules[$row]);$column++) {
		  							$style_height='';
		  							$overflow='';
		  							if(isset($modules[$row][$column]['height']) && $modules[$row][$column]['height']!=''){
			  							$style_height='height:' . $modules[$row][$column]['height'] . ';';
			  						}
		  							if(isset($modules[$row][$column]['overflow']) && $modules[$row][$column]['overflow']!=''){
			  							$overflow='overflow:' . $modules[$row][$column]['overflow'] . ';';
			  						}
		  							echo  '<div class="column" style="width:' . $modules[$row][$column]['width'] .  ';' . $style_height. $overflow . '">';			
  										echo $modules[$row][$column]['content'];
		  							echo  '</div>';  									
								} 	  						
	  							echo  '</div>';		  							
							} 
						} else {
					?>
						&nbsp;
					<?php }?>
			</div>
			