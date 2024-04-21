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
				<?php if (is_array($field)): ?>
					<label class="label"><?= GetMessage($key) ?></label>
						<?php if ($key === 'SUBJECTS'): ?>
							<?php
								$allSubjectsString = '';
								foreach ($field['ALL_SUBJECTS'] as $subjectId => $subjectTitle)
								{
									$allSubjectsString .= "<option>$subjectTitle</option>";
								}
							?>
							<?php foreach ($field['CURRENT_SUBJECTS'] as $subjectId => $subjectTitle): ?>
							<div id="subjectContainer">
								<div class="control mb-2">
									<div class="select">
										<label>
											<select class="mb-1" name="<?='current_subject_' . $subjectId?>">
													<option><?= $subjectTitle ?></option>
													<?= $allSubjectsString ?>
											</select>
										</label>
									</div>
								</div>
							</div>
							<?php endforeach; ?>
							<button class="button is-primary is-dark are-small" type="button" id="addSubject">Добавить <?= mb_strtolower(GetMessage($key)) ?></button>
						<?php else: ?>
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
						<?php endif; ?>
				<?php else: ?>
					<div class="field">
						<label class="label"><?= GetMessage($key) ?></label>
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

<script>
	document.querySelector('#addSubject').addEventListener('click', () => {
		const newListItem = document.createElement('div');
		newListItem.innerHTML = `<div class="control mb-2">
									<div class="select">
										<label>
											<select class="mb-1" name="">
													<option> Не выбрано </option>
													<?=$allSubjectsString?>
											</select>
										</label>
									</div>
								</div>`
		document.querySelector('#subjectContainer').appendChild(newListItem);
	});
</script>
