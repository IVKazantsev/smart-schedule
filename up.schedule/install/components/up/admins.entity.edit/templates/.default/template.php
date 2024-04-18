<?php

/**
 * @var array $arResult
 */
?>

<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('ENTITY_EDIT') ?>
			</div>
		</div>
	</div>

	<form action="" method="post">
		<?= bitrix_sessid_post() ?>
		<?php foreach ($arResult['ENTITY'] as $key => $field): ?>
				<div class="is-60-height box edit-fields">
					<div>
						<?= GetMessage($key) ?>:
					</div>
					<input name="$key" class="input" type="text" value="<?= $field ?>">
				</div>
		<?php endforeach; ?>

		<div class="columns">
			<div class="column is-flex is-justify-content-center">
				<button class="button" type="submit">Сохранить</button>
				<div class="button ml-2 is-danger">Удалить</div>
			</div>
		</div>
	</form>
</div>