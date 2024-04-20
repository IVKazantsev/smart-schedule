<?php

namespace Up\Schedule\Repository;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_AudienceType_Collection;

class AudienceRepository
{
	public static function getAll(): ?EO_Audience_Collection
	{
		return AudienceTable::query()
			->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])
			->fetchCollection();
	}

	public static function getAllArray(): ?array
	{
		return AudienceTable::query()
			->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])
			->fetchAll();
	}

	public static function getById(int $id): ?EO_Audience
	{
		return AudienceTable::query()
			->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])
			->where('ID', $id)
			->fetchObject();
	}

	public static function getAllTypes(): ?array
	{
		return AudienceTypeTable::query()
			->setSelect(['ID', 'TITLE',])
			->fetchAll();
	}

	public static function getArrayForAdminById(int $id): ?array
	{
		$result = AudienceTable::query()->setSelect([
			'NUMBER',
			'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE'
			])
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE_TYPE',
					AudienceTypeTable::class,
					Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
				)))
			->where('ID', $id)
			->fetch();

		if ($result === null)
		{
			return null;
		}

		$result['TYPE'] = array_unique(
			array_merge_recursive(
				[$result['TYPE']],
				array_column(self::getAllTypes(), 'TITLE')
			)
		);

		return $result;
	}


	public static function editById(int $id, array $data): void
	{
		$audience = AudienceTable::getByPrimary($id)->fetchObject();
		$type = AudienceTypeTable::query()
			->setSelect(['ID'])
			->where('TITLE', $data['TYPE'])
			->fetchObject();
		if ($data['NUMBER'] !== null)
		{
			$audience->setNumber($data['NUMBER']);
		}
		$audience->setAudienceType($type);
		$audience->save();
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		//TODO: delete function
	}
}
