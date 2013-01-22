<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $title ?></title>
		<?php if ($description) { ?>
			<meta name="description" content="<?php echo $description; ?>" />
		<?php } ?>
		<?php if ($keywords) { ?>
			<meta name="keywords" content="<?php echo $keywords; ?>" />
		<?php } ?>
		<?php if ($icon) { ?>
			<link href="<?php echo $icon; ?>" rel="icon" />
		<?php } ?>
		<?php foreach ($links as $link) { ?>
			<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
		<?php } ?>

		<link rel="stylesheet" type="text/css" href="/shoppercart/storefront/theme/default/stylesheet/layout.css" />
		<link rel="stylesheet" type="text/css" href="/shoppercart/storefront/theme/default/stylesheet/stylesheet.css" />

		<link rel="stylesheet" type="text/css" href="/shoppercart/storefront/theme/default/stylesheet/module_generated.css" />
		<script type="text/javascript" src="/shoppercart/storefront/theme/default/javascript/module_generated.js"></script>

		<?php foreach ($styles as $style) { ?>
			<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } ?>
			
		<script type="text/javascript" src="storefront/theme/javascript/jquery/jquery-1.7.1.min.js"></script>
		<?php foreach ($scripts as $script) { ?>
			<script type="text/javascript" src="<?php echo $script; ?>"></script>
		<?php } ?>

<script type="text/javascript" src="storefront/theme/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="storefront/theme/javascript/jquery/ui/external/jquery.cookie.js"></script>
		<script type="text/javascript" src="storefront/theme/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="storefront/theme/javascript/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="storefront/theme/javascript/jquery/colorbox/colorbox.css" media="screen">
	</head>
	<body>
	
	<?php echo $debug_info?>
	