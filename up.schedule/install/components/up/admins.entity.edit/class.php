<?php

use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\EO_User;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Subject;

class AdminsEntityEditComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$id = (int)$this->arParams['ID'];
		$entityName = $this->arParams['ENTITY'];
		$entity = $this->getEntityInfo($id, $entityName);
		$this->arResult['ENTITY'] = $entity;
		$this->includeComponentTemplate();
	}

	public function getEntityInfo(int $id, string $entityName): ?array
	{
		$repository = '\Up\Schedule\Repository\\' . $entityName . 'Repository';
		try
		{
			return $repository::getArrayById($id);
		}
		catch (Error)
		{
			echo "Entity $entityName not found"; die();
		}
	}
}