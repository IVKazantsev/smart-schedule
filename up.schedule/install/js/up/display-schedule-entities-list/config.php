<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/display-schedule-entities-list.bundle.css',
	'js' => 'dist/display-schedule-entities-list.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];
