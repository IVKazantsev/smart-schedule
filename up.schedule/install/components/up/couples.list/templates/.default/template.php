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
							<span><?= $arResult['CURRENT_GROUP']['TITLE'] ?></span>
						</button>
					</div>
					<div class="dropdown-menu" id="dropdown-menu" role="menu">
						<div class="dropdown-content">
							<?php foreach ($arResult['GROUPS'] as $group): ?>
								<a href="/group/<?= $group['ID'] ?>/" class="dropdown-item <?= ((int)$group['ID'] === $arResult['CURRENT_GROUP_ID']) ? 'is-active' : '' ?>"><?= $group['TITLE'] ?></a>
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
			<div class="box has-text-centered">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("MONDAY") ?>
				</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center is-fullwidth">Дифференциальные уравнения (практика)<br>Ишанов С.А.<br>404 ауд.</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">Математическое моделирование (лекция)<br>Ишанов С.А.<br>231 ауд.</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple last-couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("TUESDAY") ?>
				</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center">Математический анализ (лекция)<br>Худенко В.Н.<br>231 ауд.</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple last-couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("WEDNESDAY") ?>
				</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple last-couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("THURSDAY") ?>
				</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple last-couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("FRIDAY") ?>
				</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple last-couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
			</div>
		</div>
		<div class="column is-2">
			<div class="box has-text-centered">
				<div class="box day-of-week m-0 is-60-height is-flex is-align-items-center is-justify-content-center">
					<?= GetMessage("SATURDAY") ?>
				</div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
				<div class="box couple last-couple m-0 is-flex is-align-items-center is-justify-content-center"></div>
			</div>
		</div>
	</div>
</div>