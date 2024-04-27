<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
?>
<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('SELECT_GROUP') ?>
			</div>
		</div>
	</div>
	<div class="columns">
		<div class="column">
			<div class="mb-2">
				<div class="column is-1 p-0">
					<a id="back-button" class="is-60-height mb-5 box is-flex is-align-items-center is-justify-content-center" href="/"><?= GetMessage('BACK') ?></a>
				</div>
			</div>
			<div class="" id="group">
				<div class="box is-flex is-align-items-center is-flex-direction-column">
					<div class="columns is-60-height is-fullwidth title-of-table">
						<div class="column is-60-height is-1">
							ID
						</div>
						<div class="column is-60-height is-1">
							Название
						</div>
					</div>
					<?php foreach ($arResult['GROUPS'] as $group): ?>
						<a class="columns is-fullwidth is-60-height button has-text-left" href="/add/couple/group/<?=$group['ID']?>/select/subject/">
							<div class="column is-1">
								<?= $group['ID'] ?>
							</div>
							<div class="column">
								<?= $group['TITLE'] ?>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
