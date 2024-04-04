<?php

/**
 * @var array $arResult
 */

use Bitrix\Main\UI\Extension;

Extension::load('up.sidebar');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>
<div class="column is-one-fifth">
	<aside class="menu has-text-centered is-flex is-flex-direction-column">
		<ul class="menu-list box is-60-height">
			<?php if($arResult['IS_AUTHORIZED']): ?>
				<li class="is-60-height-child"><a class="is-60-height-child is-flex is-align-items-center is-justify-content-center" href="/profile/"><?= htmlspecialcharsbx($arResult['USER_NAME'] . ' ' . $arResult['USER_LAST_NAME']) ?><br><?= $arResult['USER_ROLE'] ?></a></li>
			<?php else: ?>
				<li class="is-60-height-child"><a class="is-60-height-child is-flex is-align-items-center is-justify-content-center has-text-weight-bold" href="/auth/">Войти</a></li>
			<?php endif; ?>
		</ul>
		<ul class="menu-list box">
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/scheduling/"><?= GetMessage("SCHEDULING") ?></a></li>
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/optimize/"><?= GetMessage("OPTIMIZE") ?></a></li>
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/statistics/"><?= GetMessage("STATISTICS") ?></a></li>
		</ul>
		<div class="box notes">
			<?= GetMessage("NOTES") ?>:
			<div class="note is-flex is-flex-direction-row columns is-centered mb-0">
				<div class="column is-2 is-flex is-align-items-center pr-0">
					<div class="note-box has-background-warning ml-auto"></div>
				</div>
				<div class="column is-9">- <?= GetMessage("BUSY_TEACHER_OR_GROUP") ?></div>
			</div>
			<div class="note is-flex is-flex-direction-row columns is-centered">
				<div class="column is-2 is-flex is-align-items-center pr-0">
					<div class="note-box has-background-danger ml-auto"></div>
				</div>
				<div class="column is-9">
					- <?= GetMessage("OCCUPIED_ROOM") ?>
				</div>
			</div>
		</div>
	</aside>
</div>
