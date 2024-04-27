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
				<div id="group-selection" class="dropdown group-selection is-60-height-child">
					<div class="dropdown-trigger group-selection-trigger is-60-height-child">
						<button id="group-selection-button" class="button is-fullwidth is-60-height-child" aria-haspopup="true" aria-controls="dropdown-menu">
							<span><?= ($arResult['CURRENT_GROUP']) ? htmlspecialcharsbx($arResult['CURRENT_GROUP']->getTitle()) : '' ?></span>
						</button>
					</div>
					<div class="dropdown-menu" id="dropdown-menu" role="menu">
						<div class="dropdown-content">
							<?php foreach ($arResult['GROUPS'] as $group): ?>
								<a href="/group/<?= $group->getId() ?>/" class="dropdown-item <?= ($group->getId() === $arResult['CURRENT_GROUP_ID']) ? 'is-active' : '' ?>"><?= htmlspecialcharsbx($group->getTitle()) ?></a>
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
<!--		--><?php //foreach (GetMessage("DAYS_OF_WEEK") as $dayNumber => $day): ?>
<!--			<div class="column is-2">-->
<!--				<div class="box has-text-centered couples">-->
<!--					<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">-->
<!--						--><?php //= $day ?>
<!--					</div>-->
<!--					--><?php //for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
<!--						<div class="box is-clickable couple m-0">-->
<!--							<div class="btn-edit-couple-container">-->
<!--								<button type="button" id="button-day---><?php //=$day?><!---number---><?php //=$i?><!--" class="btn-edit-couple button is-clickable is-small is-ghost">...</button>-->
<!--							</div>-->
<!--							<div class="couple-text">-->
<!--								--><?php //if(array_key_exists($dayNumber, $arResult['SORTED_COUPLES'])
//									&& array_key_exists($i, $arResult['SORTED_COUPLES'][$dayNumber])): ?>
<!--									--><?php //= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getSubject()->getTitle()) ?>
<!--									<br>-->
<!--									--><?php //= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getAudience()->getNumber()) ?>
<!--									<br>-->
<!--									--><?php //= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getTeacher()->getName()
//										. ' '
//										. $arResult['SORTED_COUPLES'][$dayNumber][$i]->getTeacher()->getLastName()) ?>
<!--								--><?php //endif; ?>
<!--							</div>-->
<!---->
<!--						</div>-->
<!--					--><?php //endfor; ?>
<!--				</div>-->
<!--			</div>-->
<!--		--><?php //endforeach; ?>
	</div>
</div>

<div class="modal" id="coupleModal">
	<div class="modal-background"></div>
	<div class="modal-card">
		<header class="modal-card-head">
			<!--<p class="modal-card-title" id="modal-title" >TITLE</p>-->
			<p class="modal-card-title">TITLE</p>
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
