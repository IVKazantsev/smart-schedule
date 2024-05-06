<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => './dist/validator.bundle.css',
	'js' => './dist/validator.bundle.js',
	'rel' => [
		'main.polyfill.core',
	],
	'skip_core' => true,
];
