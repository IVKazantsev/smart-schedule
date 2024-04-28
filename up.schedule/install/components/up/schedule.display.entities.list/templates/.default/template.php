<?php
use Bitrix\Main\UI\Extension;

/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

Extension::load('up.couples-list');
?>

<div class="column">
	<div class="columns">
		<div class="column is-11">
			<div class="box is-60-height">
				<div id="entity-selection" class="dropdown entity-selection is-60-height-child">
					<div class="dropdown-trigger entity-selection-trigger is-60-height-child">
						<button id="entity-selection-button" class="button is-fullwidth is-60-height-child" aria-haspopup="true" aria-controls="dropdown-menu">
							<span>
								<?= ($arResult['CURRENT_ENTITY'])
									? GetMessage($arResult['LOC_ENTITY']) . ' ' . htmlspecialcharsbx($arResult['CURRENT_ENTITY_NAME']) : GetMessage("SELECT_{$arResult['LOC_ENTITY']}") ?>
							</span>
						</button>
					</div>
					<div class="dropdown-menu" id="dropdown-menu" role="menu">
						<div class="dropdown-content">
							<?php foreach ($arResult['ENTITIES'] as $entity): ?>
								<a href="/<?= $arResult['ENTITY'] ?>/<?= $entity->getId() ?>/" class="dropdown-item <?= ($entity->getId() === $arResult['CURRENT_ENTITY_ID']) ? 'is-active' : '' ?>">
									<?= GetMessage($arResult['LOC_ENTITY']) . ' ' ?>
									<?php foreach ($arResult['ENTITY_NAME_METHODS'] as $method): ?>
										<?= htmlspecialcharsbx($entity->$method()) . ' ' ?>
									<?php endforeach; ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="column is-1">
			<a href="/add/couple/select/group/" class="box has-text-centered is-size-4 is-60-height add-couple-button">+</a>
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
				<button id="submit-form-button" type="button" class="button is-success">Сохранить</button>
				<button id="cancel-form-button" type="button" class="button">Отменить</button>
			</div>
		</footer>
	</div>
</div>

<script>
	BX.ready(function () {
		window.ScheduleCouplesList = new BX.Up.Schedule.CouplesList({
			rootNodeId: 'couples-container',
		});
	});
</script>
