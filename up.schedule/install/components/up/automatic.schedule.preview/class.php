<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Up\Schedule\Service\EntityService;

class AutomaticSchedulePreviewComponent extends CBitrixComponent
{
	private array $entitiesForDisplaySchedule = [
		'group',
		'audience',
		'teacher',
	];

	public function executeComponent(): void
	{
		// Выставляем сущность "по умолчанию"
		if (!$this->arParams['ENTITY'])
		{
			$this->arParams['ENTITY'] = 'group';
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
		$currentEntityId = (int)$this->arParams['ID'];

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
		if(!$currentEntity)
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
			'getTitle'
		];
	}

	protected function fillAudienceNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHODS'] = [
			'getNumber'
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
