<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Error;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\AudienceTypeRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\EntityService;

class AdminPanel extends Controller
{
	private const ENTITY_PER_PAGE = 5;

	public function getEntityListAction(string $entityName, int $pageNumber = 1, string $searchInput = ''): array
	{
		if($pageNumber < 1)
		{
			$pageNumber = 1;
		}

		$repository = EntityService::getEntityRepositoryName($entityName);

		if($repository === null)
		{
			$this->addError(new Error('entity must exist and be allowed', 'invalid_entity_name'));
		}

		$entityList = $repository::getPageWithArrays(self::ENTITY_PER_PAGE, $pageNumber, $searchInput);
		$countOfEntities = $repository::getCountOfEntities($searchInput);

		$doesNextPageExist = false;
		if(array_key_exists(self::ENTITY_PER_PAGE, $entityList))
		{
			$doesNextPageExist = true;
			unset($entityList[self::ENTITY_PER_PAGE]);
		}

		return [
			'pageNumber' => $pageNumber,
			'entityList' => $entityList,
			'doesNextPageExist' => $doesNextPageExist,
			'countOfEntities' => $countOfEntities,
			'entityPerPage' => self::ENTITY_PER_PAGE,
		];
	}
}
