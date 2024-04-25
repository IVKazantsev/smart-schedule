<?php

/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>

<div class="column">
	<div class="columns">
		<div class="column is-11">
			<div class="box is-60-height">
				<div class="dropdown group-selection is-60-height-child">
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
	<div class="columns">
		<?php foreach (GetMessage("DAYS_OF_WEEK") as $dayNumber => $day): ?>
			<div class="column is-2">
				<div class="box has-text-centered couples">
					<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
						<?= $day ?>
					</div>
					<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
						<div class="box is-clickable couple m-0 is-flex is-align-items-center is-flex-direction-row is-flex-wrap-wrap-reverse is-justify-content-center">
							<?php if(array_key_exists($dayNumber, $arResult['SORTED_COUPLES'])
								&& array_key_exists($i, $arResult['SORTED_COUPLES'][$dayNumber])): ?>
								<button type="button" id="button-day-<?=$day?>-number-<?=$i?>" class="btnEdit button is-clickable is-small is-ghost">...</button>
								<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getSubject()->getTitle()) ?>
								<br>
								<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getAudience()->getNumber()) ?>
								<br>
								<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getTeacher()->getName()
													   . ' '
													   . $arResult['SORTED_COUPLES'][$dayNumber][$i]->getTeacher()->getLastName()) ?>
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<script>
	/*const buttons = document.querySelectorAll('.btnEdit');

	function handleEditCoupleClick(e)
	{
		// button-day-<?=$day?>-number-<?=$i?>
		const elementId = e.target.id;
		const lengthOfSubstr = 'button-day-'.length;
		const dayId = elementId.slice(lengthOfSubstr, elementId.length);
		/!*const currentSubject = document.getElementById('current_subject_' + itemId);
		const hiddenInput = document.getElementsByName('current_subject_' + itemId);
		hiddenInput.item(0).name = 'delete_subject_' + itemId;
		currentSubject.id = 'delete_subject_' + itemId;
		currentSubject.style.display = 'none';*!/
	}

	buttons.forEach((button) => {
		button.addEventListener('click', handleDeleteClick);
	});

	const openModalButton = document.getElementById('open-modal-button');
	const closeModalButton = document.getElementById('close-modal-button');
	const overlay = document.getElementById('overlay');
	const modal = document.getElementById('modal');

	openModalButton.addEventListener('click', () => {
		openModal(modal);
	});

	overlay.addEventListener('click', () => {
		closeModal(modal);
	});

	closeModalButton.addEventListener('click', () => {
		closeModal(modal);
	});

	function openModal(modal)
	{
		if (modal === null)
		{
			return;
		}
		modal.classList.add('active');
		overlay.classList.add('active');
	}

	function closeModal(modal)
	{
		if (modal === null)
		{
			return;
		}
		modal.classList.remove('active');
		overlay.classList.remove('active');
	}*/
</script>
