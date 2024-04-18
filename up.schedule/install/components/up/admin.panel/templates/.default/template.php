<?php

use Bitrix\Main\UI\Extension;

Extension::load('up.entity-list');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>

<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('ADMIN_PANEL') ?>
			</div>
		</div>
	</div>

	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#subject">Предметы</a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#user">Пользователи</a>
		</div>
	</div>
	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#group">Группы</a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#audience">Аудитории</a>
		</div>
	</div>

	<div class="columns tabs-content">
		<div class="column">
			<div id="back-button-container">
				<a id ="back-button" class="is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" href="/admin/">Вернуться</a>
			</div>
			<div class="" id="subject">
			</div>
			<div class="" id="user">
			</div>
			<div class="" id="group">
			</div>
			<div class="" id="audience">
			</div>
		</div>
	</div>
</div>

<script>
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