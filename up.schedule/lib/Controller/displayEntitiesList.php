<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;
use Up\Schedule\Service\EntityService;

class DisplayEntitiesList extends Controller
{
	private array $entitiesForDisplaySchedule = [
		'group',
		'audience',
		'teacher',
	];

	protected function getDefaultPreFilters(): array
	{
		return [
			new HttpMethod(
				[HttpMethod::METHOD_GET, HttpMethod::METHOD_POST]
			),
			new Csrf(),
		];
	}

	public function getDisplayEntitiesListAction(string $entity = '', int $id = 0): array
	{
		if ($entity === '')
		{
			$entity = 'group';
		}
		// Обрабатываем неправильные сущности
		elseif (!in_array($entity, $this->entitiesForDisplaySchedule, true))
		{
			return [];
		}

		// Получим методы для получения названий сущностей
		$getNameMethod = "get{$entity}NameIndexes";
		$entityNameIndexes = $this->$getNameMethod();

		$repository = EntityService::getEntityRepositoryName($entity, false);

		$entities = [];
		if ($entity === 'teacher')
		{
			$currentEntity = $repository::getArrayOfTeacherById($id);
			$entities = $repository::getAllTeachersArray();
		}
		else
		{
			$currentEntity = $repository::getArrayById($id);
			$entities = $repository::getAllArray();
		}

		$locEntity = mb_strtoupper($entity);

		$entitiesCount = count($entities);
		for ($i = 0; $i < $entitiesCount; $i++)
		{
			$entities[$i]['NAMING'] = '';
		}

		$lestMethod = end($entityNameIndexes);
		foreach ($entityNameIndexes as $methodIndex)
		{
			for ($i = 0; $i < $entitiesCount; $i++)
			{
				$entities[$i]['NAMING'] .= $entities[$i][$methodIndex];

				if($methodIndex !== $lestMethod)
				{
					$entities[$i]['NAMING'] .= ' ';
				}
			}
		}

		return [
			'currentEntity' => $currentEntity,
			'entities' => $entities,
			'locEntity' => $locEntity,
		];
	}

	protected function getGroupNameIndexes(): array
	{
		return [
			'TITLE',
		];
	}

	protected function getAudienceNameIndexes(): array
	{
		return [
			'NUMBER',
		];
	}

	protected function getTeacherNameIndexes(): array
	{
		return [
			'NAME',
			'LAST_NAME',
		];
	}
}
