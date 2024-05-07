<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

class AutomaticScheduleComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$this->includeComponentTemplate();
	}
}
