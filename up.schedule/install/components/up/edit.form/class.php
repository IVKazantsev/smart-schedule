<?php
class EditFormComponent extends CBitrixComponent
{
	private array $allowedEntity = [
		'Group',
		'Subject',
		'User',
		'Audience',
	];

	public function executeComponent(): void
	{
		$this->fetchData();
		$this->includeComponentTemplate();
	}


	private function fetchData(): void
	{
		$currentEntityId = (int)$this->arParams['ID'];
		$currentEntityName = ucfirst(strtolower((string)$this->arParams['ENTITY']));
		if (!in_array($currentEntityName, $this->allowedEntity, true))
		{
			return;
		}

		$entityRepository = '\Up\Schedule\Repository\\'. $currentEntityName . "Repository";

		$entity = $entityRepository::getById($currentEntityId);


		/*$audience->getAudienceType();
		(new \Up\Schedule\Model\EO_Audience())-;*/

	}
}
