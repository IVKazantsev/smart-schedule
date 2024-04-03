<?php

/**
 * @var array $arResult
 */

use Bitrix\Main\UI\Extension;

Extension::load('up.sidebar');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}
?>
<div class="column is-one-fifth">
	<aside class="menu has-text-centered is-flex is-flex-direction-column">
		<ul class="menu-list box is-60-height">
			<li class="is-60-height-child"><a class="is-60-height-child" href="/profile/">Иванов Иван<br>Администратор</a></li>
		</ul>
		<ul class="menu-list box">
			<li><a href="/make/">Составить расписание</a></li>
			<li><a href="/optimize/">Оптимизировать расписание</a></li>
			<li><a href="/statistics/">Статистика</a></li>
		</ul>
		<div class="box notes">
			Примечания:
			<div class="note is-flex is-flex-direction-row columns is-centered mb-0">
				<div class="column is-2 is-flex is-align-items-center pr-0">
				<div class="note-box has-background-warning ml-auto"></div>
				</div>
				<div class="column is-9">- преподаватель или группа заняты</div>
			</div>
			<div class="note is-flex is-flex-direction-row columns is-centered">
				<div class="column is-2 is-flex is-align-items-center pr-0">
					<div class="note-box has-background-danger ml-auto"></div>
				</div>
				<div class="column is-9">
					- помещение занято
				</div>
			</div>
		</div>
	</aside>
</div>
