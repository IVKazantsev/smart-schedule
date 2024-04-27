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
				<?= GetMessage('COUPLE_ADDING') ?>
			</div>
		</div>
	</div>

	<div id="back-button-container" class="mb-4">
		<div class="column is-1 p-0">
			<a id ="back-button" class="is-60-height box is-flex is-align-items-center is-justify-content-center" href="
				<?= substr($APPLICATION->GetCurUri(), 0, (strpos($APPLICATION->GetCurUri(), 'subject/'))) . "select/subject/" //TODO: USE preg_replace?>
			"><?= GetMessage('BACK') ?></a>
		</div>
	</div>

	<form method="post">
		<?= bitrix_sessid_post() ?>
		<?php foreach ($arResult['DATA'] as $key => $field): ?>
		<div class="is-60-height box edit-fields">
			<?php if (is_array($field)): ?>
				<label class="label"><?= GetMessage($key) ?></label>
					<div class="control">
						<div class="select">
							<label>
								<select name="<?= $key ?>">
									<?php foreach ($field as $keyOfField => $subfield): ?>
										<option value="<?=($key === 'DAYS_OF_WEEK' || $key === 'NUMBER_IN_DAY') ? $keyOfField : $subfield['ID']?>">
											<?= ($key === 'DAYS_OF_WEEK' || $key === 'NUMBER_IN_DAY') ? $subfield : $subfield['TITLE']?>
										</option>
									<?php
									endforeach; ?>
								</select>
							</label>
						</div>
					</div>
			<?php endif; ?>
		</div>
		<?php endforeach; ?>
		<div class="columns">
			<div class="column is-flex is-justify-content-center">
				<button class="button" type="submit" formaction="<?= $APPLICATION->GetCurUri() ?>">
					<?= GetMessage('SAVE') ?>
				</button>
			</div>
		</div>
	</form>
</div>
