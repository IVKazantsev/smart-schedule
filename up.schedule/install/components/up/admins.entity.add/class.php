<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Up\Schedule\Exception\AddEntityException;
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

		$this->arResult['ENTITY'] = $this->getEntityInfo();

		$this->prepareEntityFields();
		$this->includeComponentTemplate();
	}

	private function prepareEntityFields(): void
	{
		$this->arResult['SELECTABLE_FIELDS'] = [];
		$this->arResult['INPUT_TYPES_OF_FIELDS'] = [
			'PASSWORD' => 'password',
			'CONFIRM_PASSWORD' => 'password',
			'EMAIL' => 'email',
			'DEFAULT' => 'text',
		];

		foreach ($this->arResult['ENTITY'] as $key => $field)
		{
			if (is_array($field))
			{
				$this->arResult['SELECTABLE_FIELDS'][$key] = $field;
				unset($this->arResult['ENTITY'][$key]);
			}

			if ($key === 'SUBJECTS')
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
			$this->arResult['ERRORS'] = GetMessage('SOMETHING_WENT_WRONG');

			return;
		}
		catch (AddEntityException $exception)
		{
			$this->arResult['ERRORS'] = $exception->getMessage();

			return;
		}
	}
}
