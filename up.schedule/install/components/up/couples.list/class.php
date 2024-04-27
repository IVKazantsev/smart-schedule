<?php

use Bitrix\Main\ORM\Objectify\Collection;
use Up\Schedule\AutomaticSchedule\GeneticSchedule;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\EntityService;

class CouplesListComponent extends CBitrixComponent
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
			$currentEntity = $repository::getTeacherById($currentEntityId) ? : $repository::getAnyTeacher();
			$this->arResult['ENTITIES'] = $repository::getAllTeachers();
		}
		else
		{
			$currentEntity = $repository::getById($currentEntityId) ? : $repository::getAnyObject();
			$this->arResult['ENTITIES'] = $repository::getAll();
		}
		$this->arResult['CURRENT_ENTITY_ID'] = $currentEntity['ID'];
		$this->arResult['CURRENT_ENTITY'] = $currentEntity;

		$entityNameMethod = $this->arResult['ENTITY_NAME_METHOD'];
		if (is_array($entityNameMethod))
		{
			foreach ($entityNameMethod as $method)
			{
				$this->arResult['CURRENT_ENTITY_NAME'] .= $currentEntity->$method() . ' ';
			}
		}
		else
		{
			$this->arResult['CURRENT_ENTITY_NAME'] = $currentEntity->$entityNameMethod();
		}
	}

	protected function fillGroupNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHOD'] = 'getTitle';
	}

	protected function fillAudienceNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHOD'] = 'getNumber';
	}

	protected function fillTeacherNameMethod(): void
	{
		$this->arResult['ENTITY_NAME_METHOD'] = [
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
