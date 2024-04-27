<?php

/**
 * @var string $templateFolder
 * @var array $arResult
 */

?>

<?php if(\Bitrix\Main\Context::getCurrent()->getRequest()->isPost()): ?>
	<?php if(isset($arResult['ERRORS'])): ?>
		<div class="box errors active" id="errors">
			<div class="error-title has-background-danger has-text-white is-size-4 p-3 is-flex is-justify-content-center">
				Ошибка
			</div>
			<div class="errors-text p-3">
				<?= $arResult['ERRORS'] ?>
			</div>
		</div>
	<?php else: ?>
		<div class="success box active has-background-success" id="success">
			<div class="is-60-height p-3 has-text-white is-size-4">
				Данные успешно импортированы.
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('IMPORT_TITLE') ?>
			</div>
		</div>
	</div>

	<div class="box edit-fields">
		Здесь Вы можете импортировать данные о Вашей организации из Excel-файла.<br>
		<span class="has-text-danger">Внимание: при импортировании все данные, введенные до этого, будут потеряны!</span>
	</div>

	<div class="box edit-fields">
		<form action="" method="post" id="send-excel-form" enctype="multipart/form-data">
			<?= bitrix_sessid_post() ?>
			<label class="label" for="excel-file">Вставьте сюда Excel-файл</label>
			<div class="file">
				<label class="file-label">
					<input class="file-input" type="file" name="excel-file" id="excel-file" accept=".xls, .xlsx"/>
					<span class="file-cta">
						<span class="file-label"> Выберите файл для импорта </span>
					</span>
				</label>
			</div>
		</form>
	</div>

	<div class="box edit-fields">
		Вы можете скачать шаблон Excel-файла
		<a href="<?= $templateFolder . '/template.xls' ?>" download class="is-underlined">здесь</a>.
	</div>
</div>

<script>
	document.getElementById("excel-file").onchange = function() {
		document.getElementById("send-excel-form").submit();
	};
</script>

<script>
	const successMessage = document.getElementById('success');
	const errorsMessage = document.getElementById('errors');

	if(successMessage)
	{
		setTimeout(() => { successMessage.classList.remove('active') }, 3000);
	}

	if(errorsMessage)
	{
		setTimeout(() => { errorsMessage.classList.remove('active') }, 10000);
	}
</script>