<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => './dist/popup-message.bundle.css',
	'js' => './dist/popup-message.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];
