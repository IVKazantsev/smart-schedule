<?php
use Bitrix\Main\UI\Extension;

/**
 * @var array $arResult
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

Extension::load('up.automatic-schedule');
?>

<div class="column">
	<div class="columns">
		<div class="column">
			<div class="box is-60-height is-flex is-align-items-center is-justify-content-center">
				<?= GetMessage('AUTOMATIC_SCHEDULE') ?>
			</div>
		</div>
	</div>

	<div id="automatic-schedule-container">

	</div>
</div>

<script>
	BX.ready(function () {
		window.ScheduleAutomaticSchedule = new BX.Up.Schedule.AutomaticSchedule({
			rootNodeId: 'automatic-schedule-container',
		});
	});
</script>
