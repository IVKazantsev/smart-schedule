<?php

/**
 * @var array $arResult
 */
?>

<div class="column">
	<div>
		<?php foreach ($arResult['ENTITY'] as $key => $field): ?>
			<div class="column box">
				<div>
					<?= $key ?>
				</div>
				<div>
					<?= $field ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>