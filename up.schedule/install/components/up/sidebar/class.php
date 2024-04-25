<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\AutomaticSchedule\GeneticPerson;
use Up\Schedule\AutomaticSchedule\GeneticSchedule;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
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
		$user = CurrentUser::get();
		$isAdmin = $user->isAdmin();
		$userId = $user->getId();
		$user = UserRepository::getById($userId);

		if ($user)
		{
			if($isAdmin)
			{
				$this->arResult['USER_ROLE'] = 'Администратор';
			}
			else
			{
				$this->arResult['USER_ROLE'] = $user->get('UP_SCHEDULE_ROLE')?->get('TITLE') ?? 'Гость';
			}
			$this->arResult['USER_NAME'] = $user->getName();
			$this->arResult['USER_LAST_NAME'] = $user->getLastName();

			$this->arResult['IS_AUTHORIZED'] = true;

			return;
		}
		else
		{
			$this->arResult['USER_ROLE'] = 'Гость';
		}

		$this->arResult['IS_AUTHORIZED'] = false;
	}
}
