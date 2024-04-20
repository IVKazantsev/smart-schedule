<?php

/**
 * @var array $arResult
 * @var Application $APPLICATION
 */

use Bitrix\Main\Application;

?>

<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('ENTITY_EDIT') ?>
			</div>
		</div>
	</div>

	<form method="post">
		<?= bitrix_sessid_post() ?>

		<?php foreach ($arResult['ENTITY'] as $key => $field): ?>
			<div class="is-60-height box edit-fields">
					<!--<div>
						<?php /*= GetMessage($key) */?>:
					</div>
					<input name="$key" class="input" type="text" value="<?php /*= $field */?>">-->
				<?php if (is_array($field)): ?>
					<label class="label"><?= $key ?></label>
					<div class="control">
						<div class="select">
							<label>
								<select name="<?=$key?>">
									<?php foreach ($field as $subfield): ?>
										<option><?= $subfield ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						</div>
					</div>
					<?php else: ?>
					<div class="field">
						<label class="label"><?= $key ?></label>
						<div class="control">
							<input class="input" type="text" name="<?=$key?>" placeholder="Введите данные">
						</div>
						<p class="help">
							Сейчас это поле имеет значение:
							<strong> <?=$field?> </strong>
						</p>
					</div>
					<?php endif; ?>
			</div>
		<?php endforeach; ?>

		<div class="columns">
			<div class="column is-flex is-justify-content-center">
				<button class="button" type="submit" formaction="<?=$APPLICATION->GetCurUri()?>">
					Сохранить
				</button>
				<button class="button ml-2 is-danger" type="submit"
						formaction="<?=
						str_replace("edit", 'delete', $APPLICATION->GetCurUri()) ?>">
					Удалить
				</button>
			</div>
		</div>
	</form>
</div>
