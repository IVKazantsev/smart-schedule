<?php

namespace Up\Schedule\Service;

use Bitrix\Main\Context;

class EntityService
{
	private static array $allowedEntity = [
		'Group',
		'Subject',
		'User',
		'Audience',
		'AudienceType'
	];
	public static function getEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::getArrayForAdminById($entityId);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	public static function getArrayOfRelatedEntitiesById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::getArrayOfRelatedEntitiesById($entityId);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	public static function deleteEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::deleteById($entityId);
		}
		catch (\Error)
		{
			echo "Entity $entityName not found"; die();
		}
	}
	public static function editEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::editById(
				$entityId,
				self::getData($entityName)
			);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	public static function addEntity(string $entityName)
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::add(
				self::getData($entityName)
			);
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not added"; die();
		}
	}

	public static function getEntityInfoForAdding(string $entityName): ?array
	{
		try
		{
			if ($entityName === 'AudienceType')
			{
				return null;
			}
			return self::getEntityRepositoryName($entityName)::getArrayForAdding();
		}
		catch (\Error $error)
		{
			echo "$error";
			echo "Entity $entityName not found"; die();
		}
	}

	private static function getData(string $entityName): ?array
	{
		switch ($entityName)
		{
			case 'group':
				return self::getGroupData();
			case 'audience':
				return self::getAudienceData();
			case 'user':
				return self::getUserData();
			case 'subject':
				return self::getSubjectData();
			case 'audienceType':
				return self::getAudienceTypeData();
			default:
				return null;
		}
	}

	private static function getGroupData(): ?array
	{
		//echo "<pre>";

		return [
			'TITLE' => self::getParameter('TITLE'),
			'SUBJECTS_TO_DELETE' => self::getDeleteSubjectsData(),
			'SUBJECTS_TO_ADD' => self::getAddSubjectsData(),
		];
	}

	private static function getAudienceData(): ?array
	{
		return [
			'NUMBER' => self::getParameter('NUMBER'),
			'TYPE' => self::getParameter('TYPE'),
		];
	}

	private static function getUserData(): ?array
	{
		$data = [
			'NAME' => self::getParameter('NAME'),
			'LAST_NAME' => self::getParameter('LAST_NAME'),
			'EMAIL' => self::getParameter('EMAIL'),
			'LOGIN' => self::getParameter('LOGIN'),
			'PASSWORD' => self::getParameter('PASSWORD'),
			'CONFIRM_PASSWORD' => self::getParameter('CONFIRM_PASSWORD'),
			'ROLE' => self::getParameter('ROLE'),
			'GROUP' => self::getParameter('GROUP'),
		];
		if ($data['ROLE'] === 'Преподаватель')
		{
			$data['SUBJECTS_TO_DELETE'] = self::getDeleteSubjectsData();
			$data['SUBJECTS_TO_ADD'] = self::getAddSubjectsData();
		}
		return $data;
	}

	private static function getDeleteSubjectsData(): ?array
	{
		$subjectsToDelete = [];
		foreach (Context::getCurrent()?->getRequest()->getPostList() as $key => $value)
		{
			//echo $key . "\t\t" . $value . "\n";
			if (str_starts_with($key, 'delete_subject_'))
			{
				$subjectsToDelete[] = (int)substr($key, offset: strlen('delete_subject_'));
			}
		}
		return $subjectsToDelete;
	}

	private static function getAddSubjectsData(): ?array
	{
		$subjectsToAdd = [];
		foreach (Context::getCurrent()?->getRequest()->getPostList() as $key => $value)
		{
			//echo $key . "\t\t" . $value . "\n";
			if (str_starts_with($key, 'add_subject_'))
			{
				$subjectsToAdd[] = (int)$value;
			}
		}
		return $subjectsToAdd;
	}

	private static function getSubjectData(): ?array
	{
		return [
			'TITLE' => self::getParameter('TITLE'),
			'TYPE' => self::getParameter('TYPE'),
		];
	}

	private static function getAudienceTypeData(): ?array
	{
		return [
			'TITLE' => self::getParameter('TITLE'),
		];
	}

	private static function getParameter(string $paramName): ?string
	{
		if (($param = Context::getCurrent()?->getRequest()->get($paramName)) !== '')
		{
			return $param;
		}

		return null;
	}

	private static function getEntityRepositoryName(string $entityName): ?string
	{
		$entityName = ucfirst(($entityName));
		if (!in_array($entityName, self::$allowedEntity, true))
		{
			return null;
		}

		return '\Up\Schedule\Repository\\' . $entityName . 'Repository';
	}
}
