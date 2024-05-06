<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\Engine\CurrentUser;
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
		// Выставляем сущность "по умолчанию"
		if (!$this->arParams['ENTITY'])
		{
			$userId = EntityService::getCurrentUser()->getId();
			$user = UserRepository::getById($userId);
			$roleId = $user?->get('UF_ROLE_ID');
			if (!$roleId)
			{
				$this->arParams['ENTITY'] = 'group';
				$this->includeComponentTemplate();

				return;
			}
			$role = RoleRepository::getById($roleId);

			$role = array_search($role?->getTitle(), $this->roles, true);
			if (!array_key_exists($role, $this->entityToDisplayByRole))
			{
				$this->arParams['ENTITY'] = 'group';
				$this->includeComponentTemplate();

				return;
			}

			$entity = $this->entityToDisplayByRole[$role];
			$this->arParams['ENTITY'] = $entity;

			if ($role === 'admin')
			{
				$this->includeComponentTemplate();

				return;
			}

			if($role === 'teacher')
			{
				$this->arResult['CURRENT_ENTITY_ID'] = $user?->getId();
				$this->arResult['CURRENT_ENTITY'] = $user;
			}
			else
			{
				$groupId = $user?->get('UF_GROUP_ID');
				$group = GroupRepository::getById($groupId);
				$this->arResult['CURRENT_ENTITY_ID'] = $groupId;
				$this->arResult['CURRENT_ENTITY'] = $group;
			}
		}
		// Обрабатываем неправильные сущности
		elseif (!in_array($this->arParams['ENTITY'], $this->entitiesForDisplaySchedule, true))
		{
			$this->includeComponentTemplate();

			return;
		}

		$this->prepareTemplateParams();
		$this->fetchEntityList();
		$this->includeComponentTemplate();
	}

	protected function fetchEntityList(): void
	{
		$entity = $this->arParams['ENTITY'];
		$currentEntityId = ($this->arResult['CURRENT_ENTITY_ID']) ?? (int)$this->arParams['ID'];
		if (!$currentEntityId)
		{
			$user = CurrentUser::get();
			if ($user)
			{
				$currentEntityId = $user->getId();
			}
		}

		// Получим методы для получения названий сущностей
		$fillNameMethod = "fill{$entity}NameMethod";
		$this->$fillNameMethod();

		$repository = EntityService::getEntityRepositoryName($entity, false);

		if ($entity === 'teacher')
		{
			$currentEntity = $repository::getTeacherById($currentEntityId);
			$this->arResult['ENTITIES'] = $repository::getAllTeachers();
		}
		else
		{
			$currentEntity = $repository::getById($currentEntityId);
			$this->arResult['ENTITIES'] = $repository::getAll();
		}
		$this->arResult['CURRENT_ENTITY_ID'] = $currentEntity['ID'];
		$this->arResult['CURRENT_ENTITY'] = $currentEntity;

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

	protected function fillGroupNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getTitle',
		];
	}

	protected function fillAudienceNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getNumber',
		];
	}

	protected function fillTeacherNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getName',
			'getLastName',
		];
	}

	protected function prepareTemplateParams(): void
	{
		$this->arResult['ENTITY'] = $this->arParams['ENTITY'];
		// mb_strtoupper for localization
		$this->arResult['LOC_ENTITY'] = mb_strtoupper($this->arParams['ENTITY']);
	}
}
