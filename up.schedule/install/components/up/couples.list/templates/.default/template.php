<?php

/**
 * @var array $arResult
 */
?>

<div class="column">
	<div class="columns">
		<div class="column is-11">
			<div class="box is-60-height">
				<div class="dropdown group-selection is-60-height-child">
					<div class="dropdown-trigger group-selection-trigger is-60-height-child">
						<button id="group-selection-button" class="button is-fullwidth is-60-height-child" aria-haspopup="true" aria-controls="dropdown-menu">
							<span><?= htmlspecialcharsbx($arResult['CURRENT_GROUP']->getTitle()) ?></span>
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
			<a href="/add-couple/" class="box has-text-centered is-size-4 is-60-height add-couple-button">+</a>
		</div>
	</div>
	<div class="columns">
		<div class="column is-2">
			<div class="box has-text-centered couples">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("MONDAY") ?>
				</div>
				<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
					<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
						<?php if(array_key_exists(1, $arResult['SORTED_COUPLES'])
							&& array_key_exists($i, $arResult['SORTED_COUPLES'][1])): ?>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][1][$i]->getSubject()->getTitle()) ?>
							<br>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][1][$i]->getAudience()->getNumber()) ?>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered couples">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("TUESDAY") ?>
				</div>
				<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
					<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
						<?php if(array_key_exists(2, $arResult['SORTED_COUPLES'])
							&& array_key_exists($i, $arResult['SORTED_COUPLES'][2])): ?>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][2][$i]->getSubject()->getTitle()) ?>
							<br>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][2][$i]->getAudience()->getNumber()) ?>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered couples">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("WEDNESDAY") ?>
				</div>
				<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
					<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
						<?php if(array_key_exists(3, $arResult['SORTED_COUPLES'])
							&& array_key_exists($i, $arResult['SORTED_COUPLES'][3])): ?>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][3][$i]->getSubject()->getTitle()) ?>
							<br>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][3][$i]->getAudience()->getNumber()) ?>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered couples">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("THURSDAY") ?>
				</div>
				<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
					<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
						<?php if(array_key_exists(4, $arResult['SORTED_COUPLES'])
							&& array_key_exists($i, $arResult['SORTED_COUPLES'][4])): ?>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][4][$i]->getSubject()->getTitle()) ?>
							<br>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][4][$i]->getAudience()->getNumber()) ?>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered couples">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("FRIDAY") ?>
				</div>
				<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
					<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
						<?php if(array_key_exists(5, $arResult['SORTED_COUPLES'])
							&& array_key_exists($i, $arResult['SORTED_COUPLES'][5])): ?>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][5][$i]->getSubject()->getTitle()) ?>
							<br>
							<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][5][$i]->getAudience()->getNumber()) ?>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered couples">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("SATURDAY") ?>
				</div>
				<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
					<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
						<?php if(array_key_exists(6, $arResult['SORTED_COUPLES'])
							&& array_key_exists($i, $arResult['SORTED_COUPLES'][6])): ?>
						<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][6][$i]->getSubject()->getTitle()) ?>
						<br>
						<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][6][$i]->getAudience()->getNumber()) ?>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</div>