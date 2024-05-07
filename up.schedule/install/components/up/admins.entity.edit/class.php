<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Up\Schedule\Exception\EditEntityException;
use Up\Schedule\Service\EntityService;

class AdminsEntityEditComponent extends CBitrixComponent
{
	private array $nonEditableFields = [
		'LOGIN',
	];

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

		$this->arResult['ENTITY'] = $this->getEntityInfo();

		$this->prepareEntityFields();
		$this->includeComponentTemplate();
	}

	private function prepareEntityFields(): void
	{
		$this->arResult['NON_EDITABLE_FIELDS'] = [];
		$this->arResult['SELECTABLE_FIELDS'] = [];

		foreach ($this->arResult['ENTITY'] as $key => $field)
		{
			if(in_array($key, $this->nonEditableFields, true))
			{
				$this->arResult['NON_EDITABLE_FIELDS'][$key] = $field;
				unset($this->arResult['ENTITY'][$key]);

				continue;
			}

			if(is_array($field))
			{
				$this->arResult['SELECTABLE_FIELDS'][$key] = $field;
				unset($this->arResult['ENTITY'][$key]);
			}

			if($key === 'SUBJECTS')
			{
				$this->arResult['ALL_SUBJECTS_STRING'] = '';
				foreach ($field['ALL_SUBJECTS'] as $subjectId => $subjectTitle)
				{
					$this->arResult['ALL_SUBJECTS_STRING'] .= "<option value='$subjectId'> " . str_replace(
							'`',
							'',
							htmlspecialcharsbx(
								$subjectTitle
							)
						) . "</option>";
				}
			}
		}
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
			$this->arResult['ERRORS'] = GetMessage('SESSION_EXPIRED');

			return;
		}

		$entityId = (int)Context::getCurrent()?->getRequest()->get('id');
		$entityName = Context::getCurrent()?->getRequest()->get('entity');

		if (!$entityId || !$entityName)
		{
			$this->arResult['ERRORS'] = GetMessage('EMPTY_ENTITY');

			return;
		}

		try
		{
			EntityService::editEntityById($entityName, $entityId);
			$this->arResult['SUCCESS'] = GetMessage('SUCCESS_ADDING');
		}
		catch (ArgumentException|ObjectPropertyException|SystemException)
		{
			$this->arResult['ERRORS'] = GetMessage('SOMETHING_WENT_WRONG');
			return;
		}
		catch (EditEntityException $exception)
		{
			$this->arResult['ERRORS'] = $exception->getMessage();
			return;
		}
	}
}
