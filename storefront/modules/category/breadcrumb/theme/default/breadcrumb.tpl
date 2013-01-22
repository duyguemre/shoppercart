  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?>

    	<?php if($breadcrumb==end($breadcrumbs)){
		    	echo $breadcrumb['text'];
		    	break;
	    	}
    	?>
    	<a href="<?php echo $breadcrumb['href']; ?>">
    		<?php echo $breadcrumb['text']; ?>
    	</a>
    <?php } ?>
  </div>