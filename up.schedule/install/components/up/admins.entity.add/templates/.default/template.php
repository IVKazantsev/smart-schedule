<?php

/**
 * @var array $arResult
 * @var Application $APPLICATION
 */

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

Extension::load('up.popup-message');

?>

<div id="messages"></div>

<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('ENTITY_ADD') ?>
			</div>
		</div>
	</div>

	<div id="back-button-container" class="mb-4">
		<div class="column is-1 p-0">
			<a id ="back-button"
			   class="is-60-height box is-flex is-align-items-center is-justify-content-center"
			   href="/admin/#<?= $arResult['ENTITY_NAME'] ?>">
				<?= GetMessage('BACK') ?>
			</a>
		</div>
	</div>

	<form method="post">
		<?= bitrix_sessid_post() ?>

		<?php
		foreach ($arResult['ENTITY'] as $key => $field): ?>
			<div class="is-60-height box edit-fields">
				<?php
				if (is_array($field)): ?>
					<label class="label"><?= GetMessage($key) ?></label>
					<?php
					if (GetMessage('CHANGE_' . $key . '_WARNING')): ?>
						<div class="has-text-danger"><?= GetMessage('CHANGE_' . $key . '_WARNING') ?></div>
					<?php
					endif; ?>

					<?php
					if ($key === 'SUBJECTS'): ?>
						<?php
						$allSubjectsString = '';
						foreach ($field['ALL_SUBJECTS'] as $subjectId => $subjectTitle)
						{
							$allSubjectsString .= "<option value='$subjectId'> "
								. htmlspecialcharsbx($subjectTitle)
								. "</option>";
						}
						?>
						<div id="subjectContainer">
							<?php
							if (!empty($field['CURRENT_SUBJECTS'])): ?>
								<div class="has-text-danger mb-2"><?= GetMessage('DELETE_SUBJECTS_WARNING') ?></div>
							<?php
							endif; ?>
							<?php
							foreach ($field['CURRENT_SUBJECTS'] as $subjectId => $subjectTitle): ?>
								<div class="mb-2" id="current_subject_<?= $subjectId ?>">
									<div class="box">
										<div class="p-1 is-flex is-justify-content-space-between is-flex-wrap-nowrap is-align-items-center">
											<div class="mb-2">
												<input name="current_subject_<?= $subjectId ?>" type="hidden">
												<?= htmlspecialcharsbx($subjectTitle) ?>
											</div>
											<button class="btnDelete delete is-medium" type="button" id="delete_subject_<?= $subjectId ?>"></button>
										</div>
									</div>
								</div>
							<?php
							endforeach; ?>
						</div>
						<button class="button is-primary is-dark are-small" type="button" id="addSubject">
							<?= GetMessage('ADD') ?> <?= mb_strtolower(GetMessage($key)) ?>
						</button>
					<?php else: ?>
						<div class="control">
							<div class="select">
								<label>
									<select name="<?= $key ?>">
										<?php
										foreach ($field as $subfield): ?>
											<option><?= htmlspecialcharsbx($subfield) ?></option>
										<?php
										endforeach; ?>
									</select>
								</label>
							</div>
						</div>
					<?php
					endif; ?>
				<?php
				else: ?>
					<div class="field">
						<label class="label"><?= GetMessage($key) ?></label>
						<div class="control">
							<input class="input" type="<?= ($key === 'PASSWORD') || ($key === 'CONFIRM_PASSWORD') ? 'password' : 'text'?>" name="<?= $key ?>" placeholder="Введите данные">
						</div>
					</div>
				<?php
				endif; ?>
			</div>
		<?php
		endforeach; ?>

		<div class="columns">
			<div class="column is-flex is-justify-content-center">
				<button class="button" type="submit" formaction="<?= $APPLICATION->GetCurUri() ?>">
					<?= GetMessage('ADD') ?>
				</button>
			</div>
		</div>
	</form>
</div>

<script>
	const addSubjectButton = document.querySelector('#addSubject');
	if (addSubjectButton !== null)
	{
		let i = 0;
		addSubjectButton.addEventListener('click', () => {
			const newListItem = document.createElement('div');
			newListItem.className = "mb-2";
			newListItem.innerHTML = `<div class="select">
										<label>
											<select class="mb-1" name="add_subject_`+ i +`">
													<?= $allSubjectsString ?>
											</select>
										</label>
									</div>`;
			document.querySelector('#subjectContainer').appendChild(newListItem);
			i++;
		});
	}

	roleSelect = document.querySelector("[name='ROLE']");
	if(roleSelect)
	{
		groupSelect = document.querySelector("[name='GROUP']");
		groupContainer = groupSelect.closest('.edit-fields');
		groupContainer.style.display = 'none';

		addSubject = document.getElementById('addSubject');
		subjectsContainer = addSubject.closest('.edit-fields');
		subjectsContainer.style.display = 'none';

		roleSelect.addEventListener('change', () => {
			if(roleSelect.value === 'Администратор')
			{
				groupContainer.style.display = 'none';
				subjectsContainer.style.display = 'none';
			}
			else if(roleSelect.value === 'Преподаватель')
			{
				groupContainer.style.display = 'none';
				subjectsContainer.style.display = 'block';
			}
			else if(roleSelect.value === 'Студент')
			{
				groupContainer.style.display = 'block';
				subjectsContainer.style.display = 'none';
			}
		});
	}

	BX.ready(function () {
		window.PopupMessages = new BX.Up.Schedule.PopupMessage({
			rootNodeId: 'messages',
			errorsMessage: '<?= $arResult['ERRORS'] ?>',
			successMessage: '<?= $arResult['SUCCESS'] ?>',
		});
	});
</script>