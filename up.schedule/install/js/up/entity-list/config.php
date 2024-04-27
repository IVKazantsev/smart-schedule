<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/entity-list.bundle.css',
	'js' => 'dist/entity-list.bundle.js',
	'rel' => [
		'main.core',
	],
	'skip_core' => false,
];
