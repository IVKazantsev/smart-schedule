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
		return EntityService::getEntityById($entityName, $id);
	}
}
