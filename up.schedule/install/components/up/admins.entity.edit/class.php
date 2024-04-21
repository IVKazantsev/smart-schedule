<?php

use Up\Schedule\Service\EntityService;

class AdminsEntityEditComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$entity = $this->getEntityInfo();
		$this->arResult['ENTITY'] = $entity;
		$this->includeComponentTemplate();
	}

	public function getEntityInfo(): ?array
	{
		$id = (int)$this->arParams['ID'];
		$entityName = (string)$this->arParams['ENTITY'];
		$this->arResult['ENTITY_NAME'] = $entityName;
		$this->arResult['RELATED_ENTITIES'] = EntityService::getArrayOfRelatedEntitiesById($entityName, $id);
		return EntityService::getEntityById($entityName, $id);
	}
}
