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
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#subject"><?= GetMessage('SUBJECTS') ?></a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#user"><?= GetMessage('USERS') ?></a>
		</div>
	</div>
	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#group"><?= GetMessage('GROUPS') ?></a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#audience"><?= GetMessage('AUDIENCES') ?></a>
		</div>
	</div>
	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#audienceType"><?= GetMessage('AUDIENCE_TYPE') ?></a>
		</div>
	</div>

	<div class="columns tabs-content">
		<div class="column" id="main-content-of-admin-panel">
			<div id="back-button-container" class="mb-2">
				<a id ="back-button" class="column is-1 is-offset-0 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" href="/admin/"><?= GetMessage('BACK') ?></a>
				<a id ="add-button" class="column is-offset-10 is-1 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" onclick="location.href=getEntityAddUrl()"><?= GetMessage('ADD') ?></a>
				<!--<div class="column is-full p-0">
					<div id="buttonsLinkContainer" class="columns">
						<a id ="back-button" class="column is-1 is-offset-1 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" href="/admin/"><?php /*= GetMessage('BACK') */?></a>
						<a id ="add-button" class="column is-offset-8 is-1 buttonLink is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" onclick="location.href=getEntityAddUrl()"><?php /*= GetMessage('ADD') */?></a>
					</div>
				</div>-->
			</div>
			<div class="" id="subject">
			</div>
			<div class="" id="user">
			</div>
			<div class="" id="group">
			</div>
			<div class="" id="audience">
			</div>
			<div class="" id="audienceType">
			</div>
		</div>
	</div>
</div>

<script>
	function getEntityAddUrl()
	{
		const anchor = window.location.hash;
		const entity = anchor.slice(1, anchor.length);
		return '/admin/add/' + entity + '/';
	}

	const waitForElement = (selector, callback) => {
		const observer = new MutationObserver(mutations => {
			mutations.forEach(mutation => {
				mutation.addedNodes.forEach(node => {
					if (node.matches && node.matches(selector)) {
						callback(node);
					}
				});
			});
		});

		observer.observe(document.body, { childList: true, subtree: true });
	};

	waitForElement('#entity-list-app', element => {
		BX.ready(function() {
			window.ScheduleEntityList = new BX.Up.Schedule.EntityList({
				rootNodeId: 'entity-list-app',
				entity: document.getElementById('entity-list-app').parentElement.id,
			});
		})
	});
</script>
