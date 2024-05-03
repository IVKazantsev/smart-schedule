<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/automatic-schedule.bundle.css',
	'js' => 'dist/automatic-schedule.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];
