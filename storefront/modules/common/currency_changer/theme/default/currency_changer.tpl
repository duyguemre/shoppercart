<?php if (count($currencies) > 1) { ?>
<div id="currency">
 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <?php echo $text_currency; ?><br />
    <?php foreach ($currencies as $currency) { ?>
    <?php if ($currency['code'] == $currency_code) { ?>
    <?php if ($currency['symbol_left']) { ?>
    <a title="<?php echo $currency['title']; ?>"><b><?php echo $currency['symbol_left']; ?></b></a>
    <?php } else { ?>
    <a title="<?php echo $currency['title']; ?>"><b><?php echo $currency['symbol_right']; ?></b></a>
    <?php } ?>
    <?php } else { ?>
    <?php if ($currency['symbol_left']) { ?>
    <a title="<?php echo $currency['title']; ?>" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $(this).parent().submit();"><?php echo $currency['symbol_left']; ?></a>
    <?php } else { ?>
    <a title="<?php echo $currency['title']; ?>" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>'); $(this).parent().submit();"><?php echo $currency['symbol_right']; ?></a>
    <?php } ?>
    <?php } ?>
    <?php } ?>
    <input type="hidden" name="currency_code" value="" />
</form>
 </div>
<?php } ?>
