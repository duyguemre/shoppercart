  <div id="search">
    <div class="button-search"></div>
    <input type="text" name="filter_name" value="" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
  </div>
   <div id="welcome">
    <?php echo "Welcome visitor"; ?>
  </div>
  
   <div class="links">
  <a href="<?php echo $home; ?>"><?php echo $text_home; ?></a>
  <a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a>
  <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
  <a href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a>
  <a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a>
 </div>