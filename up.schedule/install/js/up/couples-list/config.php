<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/couples-list.bundle.css',
	'js' => 'dist/couples-list.bundle.js',
	'rel' => [
		'main.core',
		'main.loader',
		'up.popup-message',
	],
	'skip_core' => false,
];
