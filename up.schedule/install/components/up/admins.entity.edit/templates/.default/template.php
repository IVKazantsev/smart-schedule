<?php

/**
 * @var array $arResult
 * @var Application $APPLICATION
 */

use Bitrix\Main\Application;
use Bitrix\Main\UI\Extension;

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
				<?= GetMessage('ENTITY_EDIT') ?>
			</div>
		</div>
	</div>

	<div id="back-button-container" class="mb-4">
		<div class="column is-1 p-0">
			<a id="back-button"
			   class="is-60-height box is-flex is-align-items-center is-justify-content-center"
			   href="/admin/#<?= $arResult['ENTITY_NAME'] ?>">
				<?= GetMessage('BACK') ?>
			</a>
		</div>
	</div>

	<?php foreach ($arResult['NON_EDITABLE_FIELDS'] as $key => $field): ?>
		<div class="is-60-height box edit-fields">
			<div class="field">
				<label class="label"><?= GetMessage($key) ?></label>
				<div class="is-size-5">
					<?= htmlspecialcharsbx($field) ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<form method="post">
		<?= bitrix_sessid_post() ?>

		<?php foreach ($arResult['ENTITY'] as $key => $field): ?>
			<div class="is-60-height box edit-fields">
				<div class="field">
					<label class="label"><?= GetMessage($key) ?></label>
					<div class="control">
						<input class="input" type="text" name="<?= $key ?>"
							   placeholder="<?= GetMessage("ENTER_$key") ?>">
					</div>
					<?php if($field): ?>
						<p class="help">
							<?= GetMessage('CURRENT_FIELD_VALUE_HELPER') ?>:
							<strong> <?= htmlspecialcharsbx($field) ?> </strong>
						</p>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

		<?php foreach ($arResult['SELECTABLE_FIELDS'] as $key => $field): ?>
			<div class="is-60-height box edit-fields">
				<div class="field">
					<label class="label"><?= GetMessage($key) ?></label>
					<?php if (GetMessage('CHANGE_' . $key . '_WARNING')): ?>
						<div class="has-text-danger"><?= GetMessage('CHANGE_' . $key . '_WARNING') ?></div>
					<?php endif; ?>

					<?php if ($key === 'SUBJECTS'): ?>
						<div id="subjectContainer">
							<?php if (!empty($field['CURRENT_SUBJECTS'])): ?>
								<div class="has-text-danger mb-2">
									<?= GetMessage('DELETE_SUBJECTS_WARNING') ?>
								</div>
							<?php endif; ?>
							<?php foreach ($field['CURRENT_SUBJECTS'] as $subjectId => $subjectTitle): ?>
								<div class="mb-2" id="current_subject_<?= $subjectId ?>">
									<div class="box">
										<div class="p-1 is-flex is-justify-content-space-between
										is-flex-wrap-nowrap is-align-items-center">
											<div class="mb-2">
												<input name="current_subject_<?= $subjectId ?>" type="hidden">
												<?= htmlspecialcharsbx($subjectTitle) ?>
											</div>
											<button class="btnDelete delete is-medium"
													type="button"
													id="delete_subject_<?= $subjectId ?>">
											</button>
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
										<?php foreach ($field as $option): ?>
											<option><?= htmlspecialcharsbx($option) ?></option>
										<?php endforeach; ?>
									</select>
								</label>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="columns">
			<div class="column is-flex is-justify-content-center">
				<button class="button" type="submit" formaction="<?= $APPLICATION->GetCurUri() ?>">
					<?= GetMessage('SAVE') ?>
				</button>
				<button data-modal-target="#modal" class="button ml-2 is-danger" id="open-modal-button" type="button">
					<?= GetMessage('DELETE') ?>
				</button>
			</div>
		</div>

		<div id="modal" class="box">
			<div class="column">
				<div class="is-size-4"><?= GetMessage('DELETION_CONFIRM_HELPER') ?></div>
				<?php if (!empty($arResult['RELATED_ENTITIES'])): ?>
					<div class="mt-3 mb-2 has-text-danger"><?= GetMessage('FIELDS_BEING_REMOVED_WARNING') ?>:</div>
					<div class="related-entities">
						<?php foreach ($arResult['RELATED_ENTITIES'] as $key => $entities): ?>
							<strong><?= GetMessage($key) ?></strong>
							<?php foreach ($entities as $entity): ?>
								<div class="box edit-fields mb-1">
									<?php foreach ($entity as $field): ?>
										<?= htmlspecialcharsbx($field) ?>
									<?php endforeach; ?>
								</div>
							<?php endforeach; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="is-flex is-align-items-center is-justify-content-center mt-2">
					<button id="delete-button" class="button is-danger" type="submit" formaction="
					<?= str_replace('edit', 'delete', $APPLICATION->GetCurUri()) ?>">
						<?= GetMessage('DELETE') ?>
					</button>
					<button id="close-modal-button" class="button ml-2" type="button">
						<?= GetMessage('CANCEL') ?>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div id="overlay"></div>

<script>
	const addSubjectButton = document.getElementById('addSubject');
	if (addSubjectButton !== null)
	{
		let i = 0;
		addSubjectButton.addEventListener('click', () => {
			const newListItem = document.createElement('div');
			newListItem.className = 'mb-2';
			newListItem.innerHTML = `<div class="select">
										<label>
											<select class="mb-1" name="add_subject_` + i + `">
													<?= $arResult['ALL_SUBJECTS_STRING'] ?>
											</select>
										</label>
									</div>`;
			document.getElementById('subjectContainer').appendChild(newListItem);
			i++;
		});
	}

	BX.ready(function() {
		window.PopupMessages = new BX.Up.PopupMessage({
			rootNodeId: 'messages',
			errorsMessage: '<?= $arResult['ERRORS'] ?>',
			successMessage: '<?= $arResult['SUCCESS'] ?>',
		});
	});
</script>

