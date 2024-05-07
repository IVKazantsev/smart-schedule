<?php

use Bitrix\Main\UI\Extension;

Extension::load('up.entity-list');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>

<div class="column is-four-fifths">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('ADMIN_PANEL') ?>
			</div>
		</div>
	</div>

	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#subject">
				<?= GetMessage('SUBJECTS') ?>
			</a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#user">
				<?= GetMessage('USERS') ?>
			</a>
		</div>
	</div>

	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#group">
				<?= GetMessage('GROUPS') ?>
			</a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#audience">
				<?= GetMessage('AUDIENCES') ?>
			</a>
		</div>
	</div>

	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#audienceType">
				<?= GetMessage('AUDIENCE_TYPE') ?>
			</a>
		</div>
	</div>

	<div class="columns tabs-content">
		<div class="column" id="main-content-of-admin-panel">
			<div id="admin-buttons-container" class="mb-2">
				<a id="back-button" class="column is-1 is-offset-0 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" href="/admin/">
					<?= GetMessage('BACK') ?>
				</a>
				<a id="add-button" class="ml-2 column is-1 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" onclick="location.href=getEntityAddUrl()">
					<?= GetMessage('ADD') ?>
				</a>
				<input id="search-input" class="input column is-60-height is-offset-4" type="text"
					   placeholder="<?= GetMessage('SEARCH_PLACEHOLDER') ?>">
				<a href="/admin/" id="search-button" class="column ml-2 is-1 is-offset-0 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage('SEARCH') ?>
				</a>
			</div>

			<div class="" id="subject"></div>
			<div class="" id="user"></div>
			<div class="" id="group"></div>
			<div class="" id="audience"></div>
			<div class="" id="audienceType"></div>
		</div>
	</div>
</div>
