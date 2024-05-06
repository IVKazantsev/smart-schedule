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
use Up\Schedule\Exception\AddEntity;
use Up\Schedule\Service\EntityService;

class AdminsEntityAddComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if (!EntityService::isCurrentUserAdmin())
		{
			LocalRedirect('/404/');
		}

		if (Context::getCurrent()?->getRequest()->isPost())
		{
			$this->processAdding();
		}

		$entity = $this->getEntityInfo();
		$this->arResult['ENTITY'] = $entity;
		$this->includeComponentTemplate();
	}

	public function getEntityInfo(): ?array
	{
		$entityName = (string)$this->arParams['ENTITY'];
		$this->arResult['ENTITY_NAME'] = $entityName;

		return EntityService::getEntityInfoForAdding($entityName);
	}

	private function processAdding(): void
	{
		if (!check_bitrix_sessid())
		{
			$this->arResult['ERRORS'] = GetMessage('SESSION_EXPIRED');

			return;
		}

		$entityName = Context::getCurrent()?->getRequest()->get('entity');
		if (!$entityName)
		{
			$this->arResult['ERRORS'] = GetMessage('NOT_SPECIFIED_ENTITY');

			return;
		}

		try
		{
			EntityService::addEntity($entityName);
			$this->arResult['SUCCESS'] = GetMessage('SUCCESS_ADDING');
		}
		catch (ArgumentException|ObjectPropertyException|SystemException)
		{
			$this->arResult['ERRORS'] = 'Что-то пошло не так';
			return;
		}
		catch (AddEntity $exception)
		{
			$this->arResult['ERRORS'] = $exception->getMessage();
			return;
		}
	}
}
