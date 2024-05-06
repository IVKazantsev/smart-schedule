<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Repository\UserRepository;

class SidebarComponent extends CBitrixComponent
{
	private array $entitiesForDisplaySchedule = [
		'group',
		'audience',
		'teacher',
	];

	public function executeComponent(): void
	{
		$this->prepareTemplateParams();
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
			$this->arResult['IS_ADMIN'] = $isAdmin;
			$this->arResult['USER_ROLE'] = $user->get('UP_SCHEDULE_ROLE')?->get('TITLE') ?? GetMessage('GUEST');

			$this->arResult['USER_NAME'] = $user->getName();
			$this->arResult['USER_LAST_NAME'] = $user->getLastName();

			$this->arResult['IS_AUTHORIZED'] = true;

			return;
		}

		$this->arResult['USER_ROLE'] = GetMessage('GUEST');

		$this->arResult['IS_ADMIN'] = false;

		$this->arResult['IS_AUTHORIZED'] = false;
	}

	protected function prepareTemplateParams(): void
	{
		$this->arResult['ENTITY'] = $this->arParams['ENTITY'];
		$this->arResult['ENTITIES_FOR_DISPLAY'] = $this->entitiesForDisplaySchedule;
		$this->arResult['LOC_ENTITIES_FOR_DISPLAY'] = array_map(static function(string $elem) {
			return strtoupper($elem);
		}, $this->entitiesForDisplaySchedule);
	}
}
