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
	];
	public static function getEntityById(string $entityName, int $entityId): ?array
	{
		try
		{
			return self::getEntityRepositoryName($entityName)::getArrayForAdminById($entityId);
		}
		catch (\Error)
		{
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
			echo "<pre>";
			echo $error;
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
			default:
				return null;
		}
	}

	private static function getGroupData(): ?array
	{
		return [
			'TITLE' => self::getParameter('TITLE'),
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
		return [
			'NAME' => self::getParameter('NAME'),
			'LAST_NAME' => self::getParameter('LAST_NAME'),
			'EMAIL' => self::getParameter('EMAIL'),
			'ROLE' => self::getParameter('ROLE'),
			'GROUP' => self::getParameter('GROUP'),
		];
	}

	private static function getSubjectData(): ?array
	{
		return [
			'TITLE' => self::getParameter('TITLE'),
			'TYPE' => self::getParameter('TYPE'),
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
		$entityName = ucfirst(strtolower($entityName));
		if (!in_array($entityName, self::$allowedEntity, true))
		{
			return null;
		}

		return '\Up\Schedule\Repository\\' . $entityName . "Repository";
	}
}
