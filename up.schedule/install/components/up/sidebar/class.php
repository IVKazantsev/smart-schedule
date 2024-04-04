<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Repository\UserRepository;

class SidebarComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$this->fetchUserInfo();
		$this->includeComponentTemplate();
	}

	protected function fetchUserInfo(): void
	{
		$userId = CurrentUser::get()->getId();
		$user = UserRepository::getById($userId);
		if ($user)
		{
			$this->arResult['USER_ROLE'] = $user->get('UP_SCHEDULE_ROLE')->get('TITLE');
			$this->arResult['USER_NAME'] = $user->getName();
			$this->arResult['USER_LAST_NAME'] = $user->getLastName();

			$this->arResult['IS_AUTHORIZED'] = true;

			return;
		}

		$this->arResult['IS_AUTHORIZED'] = false;
	}
}
