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
		<?php foreach (GetMessage("DAYS_OF_WEEK") as $dayNumber => $day): ?>
			<div class="column is-2">
				<div class="box has-text-centered couples">
					<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
						<?= $day ?>
					</div>
					<?php for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++): ?>
						<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">
							<?php if(array_key_exists($dayNumber, $arResult['SORTED_COUPLES'])
								&& array_key_exists($i, $arResult['SORTED_COUPLES'][$dayNumber])): ?>
								<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getSubject()->getTitle()) ?>
								<br>
								<?= htmlspecialcharsbx($arResult['SORTED_COUPLES'][$dayNumber][$i]->getAudience()->getNumber()) ?>
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>