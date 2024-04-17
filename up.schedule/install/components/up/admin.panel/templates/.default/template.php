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
				Административная панель
			</div>
		</div>
	</div>

	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#subjects">Предметы</a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#users">Пользователи</a>
		</div>
	</div>
	<div class="columns tabs is-active">
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#groups">Группы</a>
		</div>
		<div class="column">
			<a class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#audiences">Аудитории</a>
		</div>
	</div>

	<div class="columns tabs-content">
		<div class="column">
			<div id="back-button-container">
				<a class="is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" href="/admin/">Вернуться</a>
			</div>
			<div class="" id="subjects">
			</div>
			<div class="" id="users">
			</div>
			<div class="" id="groups">
			</div>
			<div class="" id="audiences">
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