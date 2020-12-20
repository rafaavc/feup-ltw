<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=isset($pageTitle) ? $pageTitle." | To The Rescue!" : "To The Rescue!"?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?=getRootUrl()?>/fonts/icofont/icofont.min.css">
	<link rel="stylesheet" type="text/css" href="<?=getRootUrl()?>/css/style.min.css" />
	<?php if (isset($GLOBALS['js'])) { ?>
		<!-- type="module" is automatically deferred -->
		<script type="module" src="<?=getRootUrl()?>/javascript/<?=$GLOBALS['js']?>"></script>
	<?php } ?>
</head>
<body data-csrf="<?=$_SESSION['csrf']?>">