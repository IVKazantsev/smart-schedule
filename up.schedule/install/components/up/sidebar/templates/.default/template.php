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
		<ul class="menu-list box is-120-height">
			<?php if($arResult['IS_AUTHORIZED']): ?>
				<li class="is-flex is-justify-content-space-between">
					<a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/profile/">
						<?= htmlspecialcharsbx($arResult['USER_NAME'] . ' ' . $arResult['USER_LAST_NAME']) ?><br><?= $arResult['USER_ROLE'] ?></a>
					<a class="is-60-height is-flex is-align-items-center is-justify-content-end" href="/logout/">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z"/>
							<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
							<path d="M7 12h14l-3 -3m0 6l3 -3" />
						</svg>
					</a>
				</li>
			<?php else: ?>
				<li class="has-text-centered">
					<a class="is-60-height has-text-weight-bold" href="/login/">
						Войти
					</a>
				</li>
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
