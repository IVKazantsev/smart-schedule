<?php

/**
 * @var string $templateFolder
 * @var array $arResult
 */

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
				<?= GetMessage('IMPORT_TITLE') ?>
			</div>
		</div>
	</div>

	<div class="box edit-fields">
		<?= GetMessage('ORGANIZATION_DATA_IMPORT_MESSAGE') ?><br>
		<span class="has-text-danger"><?= GetMessage('DATA_DESTRUCTION_WARNING') ?></span>
	</div>

	<div class="box edit-fields">
		<form action="" method="post" id="send-excel-form" enctype="multipart/form-data">
			<?= bitrix_sessid_post() ?>
			<label class="label" for="excel-file"><?= GetMessage('INSERT_FILE_MESSAGE') ?></label>
			<div class="file">
				<label class="file-label">
					<input class="file-input" type="file" name="excel-file" id="excel-file" accept=".xls, .xlsx"/>
					<span class="file-cta">
						<span class="file-label"> <?= GetMessage('CHOOSE_FILE_MESSAGE') ?> </span>
					</span>
				</label>
			</div>
		</form>
	</div>

	<div class="box edit-fields">
		<?= GetMessage('DOWNLOAD_TEMPLATE_MESSAGE') ?>
		<a href="<?= $templateFolder . '/template.xls' ?>" download class="is-underlined"><?= GetMessage('HERE') ?></a>.
	</div>
</div>

<script>
	document.getElementById("excel-file").onchange = function() {
		document.getElementById("send-excel-form").submit();
	};

	BX.ready(function () {
		window.PopupMessages = new BX.Up.Schedule.PopupMessage({
			rootNodeId: 'messages',
			errorsMessage: '<?= $arResult['ERRORS'] ?>',
			successMessage: '<?= $arResult['SUCCESS'] ?>',
		});
	});
</script>