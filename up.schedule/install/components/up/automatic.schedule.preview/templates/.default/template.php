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
						<input id="entity-selection-button"
							   class="button is-fullwidth is-60-height-child"
							   aria-haspopup="true"
							   aria-controls="dropdown-menu"
							   placeholder="<?= ($arResult['CURRENT_ENTITY_NAME'])
								   ? GetMessage($arResult['LOC_ENTITY']) . ' '
								   . htmlspecialcharsbx($arResult['CURRENT_ENTITY_NAME'])
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

<script>
	BX.ready(function() {
		window.ScheduleCouplesList = new BX.Up.CouplesList(
			{ rootNodeId: 'couples-container' },
			false,
		);

		window.DisplayEntitiesList = new BX.Up.DisplayScheduleEntitiesList({
				rootNodeId: 'dropdown-menu-container',
				entity: window.ScheduleCouplesList.entity,
				entityId: window.ScheduleCouplesList.entityId,
				scheduleCouplesList: window.ScheduleCouplesList,
			},
			false,
		);

		const entityButtons = document.querySelectorAll('.display-entity');
		entityButtons.forEach((button) => {
			button.addEventListener('click', () => {
				window.ScheduleCouplesList.extractEntityFromUrl();
				window.ScheduleCouplesList.reload();

				const entityId = window.ScheduleCouplesList.entityId;
				const entity = window.ScheduleCouplesList.entity;

				window.DisplayEntitiesList.reload({'entityId': entityId, 'entity': entity});
			});
		});
	});
</script>
