<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Up\Schedule\Exception\EditEntity;
use Up\Schedule\Service\EntityService;

class AdminsEntityEditComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if (!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}

		if (Context::getCurrent()?->getRequest()->isPost())
		{
			$this->processEditing();
		}

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

	private function processEditing(): void
	{
		if (!check_bitrix_sessid())
		{
			$this->arResult['ERRORS'] = 'Сессия истекла';

			return;
		}

		$entityId = (int)Context::getCurrent()?->getRequest()->get('id');
		$entityName = Context::getCurrent()?->getRequest()->get('entity');

		if (!$entityId || !$entityName)
		{
			$this->arResult['ERRORS'] = 'Не задана сущность для редактирования';

			return;
		}

		try
		{
			EntityService::editEntityById($entityName, $entityId);
			$this->arResult['SUCCESS'] = GetMessage('SUCCESS_ADDING');
		}
		catch (ArgumentException|ObjectPropertyException|SystemException)
		{
			$this->arResult['ERRORS'] = 'Что-то пошло не так';
			return;
		}
		catch (EditEntity $exception)
		{
			$this->arResult['ERRORS'] = $exception->getMessage();
			return;
		}
	}
}
