<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/groups-list.bundle.css',
	'js' => 'dist/groups-list.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];
