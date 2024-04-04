<?php

class SidebarComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$this->fetchUserInfo();
		$this->includeComponentTemplate();
	}

	protected function fetchUserInfo()
	{
		$userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();
		$user = \Up\Schedule\Repository\UserRepository::getById($userId);
		$this->arResult['USER_ROLE'] = $user['ROLE'];
		$this->arResult['USER_NAME'] = $user['NAME'];
		$this->arResult['USER_LAST_NAME'] = $user['LAST_NAME'];
	}
}
