<?php

class SidebarComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		// $this->fetchSidebarList();
		$this->includeComponentTemplate();
	}

	// protected function fetchSidebarList(): void
	// {
	// 	$items = [
	// 		'user' =>
	// 		[
	// 			'href' => 'profile',
	// 			'title' => 'Иванов Иван',
	// 		],
	//
	// 		[
	// 			'href' => 456,
	// 			'title' => 'Projector - simple control for tasks',
	// 		],
	// 	];
	//
	// 	$this->arResult['SIDEBAR_ITEMS'] = $items;
	// }
}