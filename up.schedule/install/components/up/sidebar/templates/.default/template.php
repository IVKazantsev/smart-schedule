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
				<li class="is-flex is-justify-content-flex-start">
					<a class="is-60-height is-flex is-align-items-center is-justify-content-center is-flex-grow-5" href="/profile/">
						<?= htmlspecialcharsbx($arResult['USER_NAME'] . ' ' . $arResult['USER_LAST_NAME']) ?><br><?= htmlspecialcharsbx($arResult['USER_ROLE']) ?></a>
					<a class="is-60-height is-flex is-align-items-center is-justify-content-end" href="/logout/">
						<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z"/>
							<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
							<path d="M7 12h14l-3 -3m0 6l3 -3" />
						</svg>
					</a>
				</li>
			<?php else: ?>
				<li class="is-flex is-justify-content-flex-start">
					<div class="is-60-height is-flex is-align-items-center is-justify-content-center is-flex-grow-5">
						<?= htmlspecialcharsbx($arResult['USER_ROLE']) ?>
					</div>
					<a class="is-60-height is-flex is-align-items-center is-justify-content-end" href="/login/">
						<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke="currentColor" stroke-width="2" class="icon icon-tabler icon-tabler-logout">
							<path id="svg_2" d="m14,8l0,-2a2,2 0 0 0 -2,-2l-7,0a2,2 0 0 0 -2,2l0,12a2,2 0 0 0 2,2l7,0a2,2 0 0 0 2,-2l0,-2"/>
							<path transform="rotate(-179.895 14 12)" id="svg_3" d="m7,12l14,0l-3,-3m0,6l3,-3"/>
						</svg>
					</a>
				</li>
			<?php endif; ?>
		</ul>
		<?php if($arResult['USER_ROLE'] === 'Администратор'): ?>
		<ul class="menu-list box">
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/admin/"><?= GetMessage("ADMIN_PANEL") ?></a></li>
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/scheduling/"><?= GetMessage("SCHEDULING") ?></a></li>
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/"><?= GetMessage("BACK_TO_SCHEDULE") ?></a></li>
			<li><a class="is-60-height is-flex is-align-items-center is-justify-content-center" href="/import/"><?= GetMessage("IMPORT_FROM_EXCEL") ?></a></li>
		</ul>
		<div class="box notes">
			<div class="m-3">
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
		</div>
		<?php endif; ?>

		<?php if($arResult['ENTITY']): ?>
			<ul class="menu-list box">
				<div class="mt-3 mb-3 is-flex is-align-items-center is-justify-content-center is-fullwidth"><?= GetMessage('DISPLAY_BY') ?>:</div>
				<?php foreach ($arResult['ENTITIES_FOR_DISPLAY'] as $key => $entity): ?>
					<li>
						<a href="/<?= $entity ?>/1/" class="display-entity is-60-height is-flex is-align-items-center is-justify-content-center <?= ($arResult['ENTITY'] === $entity) ? 'selected-sidebar-entity' : '' ?>">
							<?= GetMessage('SIDEBAR_' . $arResult['LOC_ENTITIES_FOR_DISPLAY'][$key]) ?>
						</a>
					</li>
				<?php endforeach; ?>
				<div class="selected-indicator"></div> <!-- Добавленный блок для индикатора -->
			</ul>

		<?php endif; ?>
	</aside>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		const indicator = document.querySelector('.selected-indicator');
		let selectedLink = document.querySelector('.selected-sidebar-entity');

		function moveIndicator() {
			indicator.style.top = selectedLink.offsetTop + "px";
			indicator.style.width = selectedLink.offsetWidth + "px";
			indicator.style.height = selectedLink.offsetHeight + "px";
		}

		moveIndicator();

		const links = document.querySelectorAll('.display-entity');
		links.forEach(function(link) {
			link.addEventListener('click', function(event) {
				event.preventDefault();
				selectedLink.classList.remove('selected-sidebar-entity');
				selectedLink = this;
				selectedLink.classList.add('selected-sidebar-entity');
				moveIndicator();

				if (history.pushState) {
					const newUrl = link.href;
					window.history.pushState({path:newUrl},'',newUrl);
				}
			});
		});
	});
</script>