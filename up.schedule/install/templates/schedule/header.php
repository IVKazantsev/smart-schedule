<?php

/**
 * @var CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>

<!doctype html>
<html lang="<?= LANGUAGE_ID ?>" class="has-background-grey-lighter">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php $APPLICATION->ShowTitle(); ?></title>
	<?php $APPLICATION->ShowHead(); ?>
</head>
<body class="has-background-grey-lighter">
<?php $APPLICATION->ShowPanel(); ?>
<div class="container mt-4" id="main-container">
	<div class="columns">