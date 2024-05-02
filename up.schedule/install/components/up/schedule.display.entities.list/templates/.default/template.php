<?php
use Bitrix\Main\UI\Extension;

/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

Extension::load('up.display-schedule-entities-list');
Extension::load('up.couples-list');
?>

<div class="column is-four-fifths">
	<div class="columns">
		<div class="column entity-selection-container">
			<div class="box is-60-height">
				<div id="entity-selection" class="dropdown entity-selection is-60-height-child">
					<div class="dropdown-trigger entity-selection-trigger is-60-height-child">
						<input id="entity-selection-button" class="button is-fullwidth is-60-height-child" aria-haspopup="true" aria-controls="dropdown-menu"
								placeholder="<?= ($arResult['CURRENT_ENTITY'])
									? GetMessage($arResult['LOC_ENTITY']) . ' ' . htmlspecialcharsbx($arResult['CURRENT_ENTITY_NAME'])
									: GetMessage("SELECT_{$arResult['LOC_ENTITY']}") ?>"
						>
					</div>
					<div class="dropdown-menu" id="dropdown-menu" role="menu">
						<div id="dropdown-menu-container" class="dropdown-content">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="couples-container" class="columns">
	</div>
</div>

<div class="modal" id="coupleModal">
	<div class="modal-background"></div>
	<div class="modal-card">
		<header class="modal-card-head">
			<p class="modal-card-title">Добавление пары</p>
			<button class="delete" aria-label="close" id="button-close-modal"></button>
		</header>
		<section id="modal-body" class="modal-card-body">
			<form id="add-edit-form">
				<?= bitrix_sessid_post() ?>
			</form>
		</section>
		<footer class="modal-card-foot">
			<div class="buttons">
				<button id="submit-form-button" type="button" class="button is-success"><?= GetMessage('SAVE') ?></button>
				<button id="cancel-form-button" type="button" class="button"><?= GetMessage('CANCEL') ?></button>
			</div>
		</footer>
	</div>
</div>

<script>
	BX.ready(function () {
		window.ScheduleCouplesList = new BX.Up.Schedule.CouplesList({
			rootNodeId: 'couples-container',
		});
		window.DisplayEntitiesList = new BX.Up.Schedule.DisplayScheduleEntitiesList({
			rootNodeId: 'dropdown-menu-container',
			entityInfo: window.ScheduleCouplesList.extractEntityFromUrl(),
		});

		const entityButtons = document.querySelectorAll('.display-entity');
		entityButtons.forEach((button) => {
			button.addEventListener('click', () => {
				const address = button.href;
				const addresses = address.split('/');
				const entityIndex = addresses.findIndex((element, index, array) => {
					const needles = [
						'group',
						'teacher',
						'audience',
					];

					return needles.includes(element);
				});

				const entityIdIndex = entityIndex + 1;

				let entity = addresses[entityIndex];
				let entityId = addresses[entityIdIndex];

				entityId = typeof Number(entityId) === 'number' ? entityId : undefined;
				entity = typeof entity === 'string' ? entity : undefined;

				const entityInfo = {
					'entityId': entityId,
					'entity': entity,
				};

				window.ScheduleCouplesList.entityId = entityId;
				window.ScheduleCouplesList.entity = entity;
				window.ScheduleCouplesList.reload();
				window.DisplayEntitiesList.reload(entityInfo);
			})
		})
	});

	const dropdown = document.querySelector('.dropdown');
	const entitySelectionInput = document.getElementById('entity-selection-button');
	entitySelectionInput.addEventListener('input', () => {
		window.DisplayEntitiesList.reload([], entitySelectionInput.value);
		dropdown.classList.add('is-active');
	})
</script>
