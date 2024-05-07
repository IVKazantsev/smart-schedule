<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\RoleRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\EntityService;

class CouplesListComponent extends CBitrixComponent
{
	private array $entitiesForDisplaySchedule = [
		'group',
		'audience',
		'teacher',
	];

	private array $roles = [
		'student' => 'Студент',
		'admin' => 'Администратор',
		'teacher' => 'Преподаватель',
	];

	private array $entityToDisplayByRole = [
		'student' => 'group',
		'admin' => 'group',
		'teacher' => 'teacher',
	];

	public function executeComponent(): void
	{
		$this->defineEntityToDisplay();
		$this->prepareTemplateParams();
		$this->fillEntityInfo();
		$this->includeComponentTemplate();
	}

	private function defineEntityToDisplay(): void
	{
		if ($this->arParams['ENTITY'])
		{
			if (!in_array($this->arParams['ENTITY'], $this->entitiesForDisplaySchedule, true))
			{
				$this->arParams['ENTITY'] = DEFAULT_ENTITY_TO_DISPLAY;
				$this->arResult['CURRENT_ENTITY_ID'] = 0;
			}

			return;
		}

		$userId = EntityService::getCurrentUser()->getId();
		$user = UserRepository::getById($userId);
		$roleId = $user?->get('UF_ROLE_ID');
		if (!$roleId)
		{
			$this->arParams['ENTITY'] = DEFAULT_ENTITY_TO_DISPLAY;

			return;
		}
		$role = RoleRepository::getById($roleId);

		$role = array_search($role?->getTitle(), $this->roles, true);
		if (!array_key_exists($role, $this->entityToDisplayByRole))
		{
			$this->arParams['ENTITY'] = DEFAULT_ENTITY_TO_DISPLAY;

			return;
		}

		$entity = $this->entityToDisplayByRole[$role];
		$this->arParams['ENTITY'] = $entity;

		if ($role === 'admin')
		{
			return;
		}

		if ($role === 'teacher')
		{
			$this->arResult['CURRENT_ENTITY_ID'] = (int)$user?->getId();
			$this->arResult['CURRENT_ENTITY'] = $user;

			return;
		}

		$groupId = $user?->get('UF_GROUP_ID');
		$group = GroupRepository::getById($groupId);
		$this->arResult['CURRENT_ENTITY_ID'] = $groupId;
		$this->arResult['CURRENT_ENTITY'] = $group;
	}

	private function fillEntityInfo(): void
	{
		$entity = $this->arParams['ENTITY'];
		$this->arResult['CURRENT_ENTITY_ID'] = ($this->arResult['CURRENT_ENTITY_ID']) ?? (int)$this->arParams['ID'];

		// Получим методы для получения названий сущностей
		$fillNameMethod = "fill{$entity}NameMethods";
		$this->$fillNameMethod();

		$repository = EntityService::getEntityRepositoryName($entity, false);

		if ($entity === 'teacher')
		{
			$currentEntity = $repository::getTeacherById($this->arResult['CURRENT_ENTITY_ID']);
		}
		else
		{
			$currentEntity = $repository::getById($this->arResult['CURRENT_ENTITY_ID']);
		}

		if ($currentEntity)
		{
			$this->arResult['CURRENT_ENTITY_ID'] = $currentEntity['ID'];
		}

		$entityNameMethods = $this->arResult['ENTITY_NAME_METHODS'];
		if (!$currentEntity)
		{
			return;
		}
		foreach ($entityNameMethods as $method)
		{
			$this->arResult['CURRENT_ENTITY_NAME'] .= $currentEntity->$method() . ' ';
		}
	}

	private function fillGroupNameMethods(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getTitle',
		];
	}

	private function fillAudienceNameMethods(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getNumber',
		];
	}

	private function fillTeacherNameMethods(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getName',
			'getLastName',
		];
	}

	private function prepareTemplateParams(): void
	{
		$this->arResult['ENTITY'] = $this->arParams['ENTITY'];
		// mb_strtoupper for localization
		$this->arResult['LOC_ENTITY'] = mb_strtoupper($this->arParams['ENTITY']);
	}
}
