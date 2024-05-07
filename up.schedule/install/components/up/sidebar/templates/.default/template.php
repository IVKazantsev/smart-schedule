<?php

/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

?>

<div class="column is-one-fifth">
	<aside class="menu has-text-centered is-flex is-flex-direction-column">
		<ul class="menu-list box is-120-height">
			<?php  if ($arResult['IS_AUTHORIZED']): ?>
				<li class="is-flex is-justify-content-flex-start">
					<div id="user-info"
						 class="column is-10
						 is-60-height is-flex
						 is-flex-direction-column
						 is-align-items-center
						 is-justify-content-center
						 is-flex-grow-5">
						<div class="user-info-container">
							<?= htmlspecialcharsbx($arResult['USER_NAME'] . ' ' . $arResult['USER_LAST_NAME']) ?>
						</div>
						<div class="user-info-container">
							<?= htmlspecialcharsbx($arResult['USER_ROLE']) ?>
						</div>
					</div>
					<a class="column p-1 is-2 is-60-height is-flex is-align-items-center is-justify-content-center"
					   href="/logout/">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							class="icon icon-tabler icon-tabler-logout"
							width="24" height="24"
							viewBox="0 0 24 24" stroke-width="2"
							stroke="currentColor" fill="none"
							stroke-linecap="round" stroke-linejoin="round">
							<path stroke="none" d="M0 0h24v24H0z"/>
							<path
								d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/>
							<path d="M7 12h14l-3 -3m0 6l3 -3"/>
						</svg>
					</a>
				</li>
			<?php else: ?>
				<li class="is-flex is-justify-content-flex-start">
					<div id="user-info"
						 class="column is-10
						 is-60-height is-flex
						 is-flex-direction-column
						 is-align-items-center
						 is-justify-content-center
						 is-flex-grow-5">
						<?= htmlspecialcharsbx($arResult['USER_ROLE']) ?>
					</div>
					<a class="column p-1 is-2 is-60-height is-flex is-align-items-center is-justify-content-center"
					   href="/login/">
						<svg width="24" height="24"
							 xmlns="http://www.w3.org/2000/svg"
							 stroke-linejoin="round"
							 stroke-linecap="round" fill="none"
							 stroke="currentColor"
							 stroke-width="2"
							 class="icon icon-tabler icon-tabler-logout">
							<path
								id="svg_2"
								d="m14,8l0,-2a2,2 0 0 0 -2,-2l-7,0a2,2 0 0 0 -2,
								2l0,12a2,2 0 0 0 2,2l7,0a2,2 0 0 0 2,-2l0,-2"/>
							<path
								transform="rotate(-179.895 14 12)"
								id="svg_3"
								d="m7,12l14,0l-3,-3m0,6l3,-3"/>
						</svg>
					</a>
				</li>
			<?php endif; ?>
		</ul>

		<?php if ($arResult['IS_ADMIN']): ?>
			<ul class="menu-list box">
				<li>
					<a class="pl-2 pr-2 is-60-height is-flex is-align-items-center is-justify-content-center"
					   href="/admin/">
						<div class="column is-2 p-0">
							<svg class="sidebar-icon" version="1.1" id="Layer_1"
								 xmlns="http://www.w3.org/2000/svg"
								 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
							<g>
								<polygon fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10"
										 points="32,1 26,1 26,10 20,12 14,6 6,14 12,20
									10,26 1,26 1,38 10,38 12,44 6,50 14,58 20,52 26,54 26,63 32,63 38,63 38,54 44,
									52 50,58 58,50 52,44 54,38 63,38 63,26 54,26
									52,20 58,14 50,6 44,12 38,10 38,1 	"/>
								<circle fill="none" stroke="#000000" stroke-width="2"
										stroke-miterlimit="10" cx="32" cy="32" r="6"/>
							</g>
							</svg>
						</div>
						<div class="column is-10 pl-2 has-text-left p-0">
							<?= GetMessage("ADMIN_PANEL") ?>
						</div>
					</a>
				</li>
				<li>
					<a class="pl-2 pr-2 is-60-height is-flex is-align-items-center is-justify-content-center"
					   href="/scheduling/">
						<div class="column is-2 p-0">
							<svg class="sidebar-icon" version="1.1" id="Layer_1"
								 xmlns="http://www.w3.org/2000/svg"
								 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
							<g>
								<line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10"
									  x1="46" y1="10" x2="18" y2="10"/>
								<polyline fill="none" stroke="#000000" stroke-width="2"
										  stroke-miterlimit="10" points="12,10 1,10 1,58 63,58 63,10 52,10 	"/>
								<rect x="12" y="6" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="6" height="8"/>
								<rect x="46" y="6" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="6" height="8"/>
								<rect x="10" y="24" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="10" height="10"/>
								<rect x="10" y="42" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="10" height="10"/>
								<rect x="44" y="24" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="10" height="10"/>
								<rect x="44" y="42" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="10" height="10"/>
								<rect x="27" y="24" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="10" height="10"/>
								<rect x="27" y="42" fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" width="10" height="10"/>
							</g>
								<line fill="none" stroke="#000000"
									  stroke-width="2" stroke-miterlimit="10" x1="1" y1="18" x2="63" y2="18"/>
							</svg>
						</div>
						<div class="column is-10 pl-2 has-text-left p-0">
							<?= GetMessage("SCHEDULING") ?>
						</div>
					</a>
				</li>
				<li>
					<a class="pl-2 pr-2 is-60-height is-flex is-align-items-center is-justify-content-center"
					   href="/">
						<div class="column is-2 p-0">
							<svg class="sidebar-icon" version="1.1" id="Layer_1"
								 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
								 x="0px" y="0px"
								 viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
							<polygon fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10"
									 points="32,3 2,33 11,33 11,63 23,63 23,47 39,47
									 39,63 51,63 51,33 62,33 "/>
							</svg>
						</div>
						<div class="column is-10 pl-2 has-text-left p-0">
							<?= GetMessage("BACK_TO_SCHEDULE") ?>
						</div>
					</a>
				</li>
				<li>
					<a class="pl-2 pr-2 is-60-height is-flex is-align-items-center is-justify-content-center"
					   href="/import/">
						<div class="column is-2 p-0">
							<svg class="sidebar-icon" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
								 xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
								<polyline fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10"
										  points="5,41 11,1 53,1 59,41 "/>
								<rect x="5" y="41" fill="none" stroke="#000000" stroke-width="2"
									  stroke-miterlimit="10" width="54" height="22"/>
								<circle fill="none" stroke="#000000" stroke-width="2"
										stroke-miterlimit="10" cx="48" cy="52" r="3"/>
								<polyline fill="none" stroke="#000000" stroke-width="2"
										  stroke-linejoin="bevel" stroke-miterlimit="10" points="40,23 32,31
								24,23 "/>
								<g>
									<line fill="none" stroke="#000000" stroke-width="2"
										  stroke-miterlimit="10" x1="32" y1="31" x2="32" y2="11"/>
								</g>
							</svg>
						</div>
						<div class="column is-10 pl-2 has-text-left p-0">
							<?= GetMessage("IMPORT_FROM_EXCEL") ?>
						</div>
					</a>
				</li>
			</ul>
		<?php endif; ?>

		<?php if ($arResult['ENTITY']): ?>
			<ul class="menu-list box" id="schedule-display-entity-list">
				<div class="mt-3 mb-3 is-flex is-align-items-center is-justify-content-center is-fullwidth">
					<?= GetMessage('DISPLAY_BY') ?>:
				</div>
				<?php foreach ($arResult['ENTITIES_FOR_DISPLAY'] as $key => $entity): ?>
					<li>
						<a href="/<?= $entity ?>/0/"
						   class="display-entity is-60-height is-flex is-align-items-center is-justify-content-center
						   <?= ($arResult['ENTITY'] === $entity) ? 'selected-sidebar-entity' : '' ?>">
							<?= GetMessage('SIDEBAR_' . $arResult['LOC_ENTITIES_FOR_DISPLAY'][$key]) ?>
						</a>
					</li>
				<?php endforeach; ?>
				<div class="selected-indicator"></div>
			</ul>
		<?php endif; ?>
	</aside>
</div>