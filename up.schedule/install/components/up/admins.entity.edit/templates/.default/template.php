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

	<div id="back-button-container" class="mb-4">
		<div class="column is-1 p-0">
			<a id ="back-button" class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="/admin/#<?= $arResult['ENTITY_NAME'] ?>">Назад</a>
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
					if ($key === 'SUBJECTS'): ?>
						<?php
						$allSubjectsString = '';
						foreach ($field['ALL_SUBJECTS'] as $subjectId => $subjectTitle)
						{
							$allSubjectsString .= "<option>$subjectTitle</option>";
						}
						?>
						<?php
						foreach ($field['CURRENT_SUBJECTS'] as $subjectId => $subjectTitle): ?>
							<div id="subjectContainer">
								<div class="control mb-2">
									<div class="select">
										<label>
											<select class="mb-1" name="<?= 'current_subject_' . $subjectId ?>">
												<option><?= $subjectTitle ?></option>
												<?= $allSubjectsString ?>
											</select>
										</label>
									</div>
								</div>
							</div>
						<?php
						endforeach; ?>
						<button class="button is-primary is-dark are-small" type="button" id="addSubject">Добавить <?= mb_strtolower(
								GetMessage($key)
							) ?></button>
					<?php
					else: ?>
						<div class="control">
							<div class="select">
								<label>
									<select name="<?= $key ?>">
										<?php
										foreach ($field as $subfield): ?>
											<option><?= $subfield ?></option>
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
							<input class="input" type="text" name="<?= $key ?>" placeholder="Введите данные">
						</div>
						<p class="help">
							Сейчас это поле имеет значение:
							<strong> <?= $field ?> </strong>
						</p>
					</div>
				<?php
				endif; ?>
			</div>
		<?php
		endforeach; ?>

		<div class="columns">
			<div class="column is-flex is-justify-content-center">
				<button class="button" type="submit" formaction="<?= $APPLICATION->GetCurUri() ?>">
					Сохранить
				</button>
				<button data-modal-target="#modal" class="button ml-2 is-danger" id="open-modal-button" type="button">
					Удалить
				</button>
			</div>
		</div>

		<div id="modal" class="box">
			<div class="column">
				<div class="is-size-4">Вы действительно хотите удалить данный элемент?</div>
				<?php if (!empty($arResult['RELATED_ENTITIES'])): ?>
					<div class="mt-3 mb-2 has-text-danger">При его удалении, удалится следующее:</div>
						<div class="related-entities">
							<?php foreach ($arResult['RELATED_ENTITIES'] as $key => $entity): ?>
								<strong><?= GetMessage($key) ?></strong>
								<?php foreach ($entity as $exemplar): ?>
									<div class="box edit-fields mb-1">
										<?php foreach ($exemplar as $field): ?>
										<?= $field ?>
										<?php endforeach; ?>
									</div>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</div>
				<?php endif; ?>
				<div class="is-flex is-align-items-center is-justify-content-center mt-2">
					<button id="delete-button" class="button is-danger" type="submit" formaction="<?=
					str_replace('edit', 'delete', $APPLICATION->GetCurUri()) ?>"
					>
					Удалить</button>
					<button id="close-modal-button" class="button ml-2" type="button">Отменить</button>
				</div>
			</div>
		</div>
	</form>
</div>

<div id="overlay"></div>

<script>
	const addSubjectButton = document.querySelector('#addSubject');
	if (addSubjectButton !== null)
	{
		addSubjectButton.addEventListener('click', () => {
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
								</div>`;
			document.querySelector('#subjectContainer').appendChild(newListItem);
		});
	}
</script>

<script>
	const openModalButton = document.getElementById('open-modal-button');
	const closeModalButton = document.getElementById('close-modal-button');
	const overlay = document.getElementById('overlay');
	const modal = document.getElementById('modal');

	openModalButton.addEventListener('click', () => {
		openModal(modal);
	});

	overlay.addEventListener('click', () => {
		closeModal(modal);
	});

	closeModalButton.addEventListener('click', () => {
		closeModal(modal);
	});

	function openModal(modal)
	{
		if (modal === null)
		{
			return;
		}
		modal.classList.add('active');
		overlay.classList.add('active');
	}

	function closeModal(modal)
	{
		if (modal === null)
		{
			return;
		}
		modal.classList.remove('active');
		overlay.classList.remove('active');
	}
</script>
