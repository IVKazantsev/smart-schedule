<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;

class AudienceTypeRepository
{
	public static function getArrayForAdminById(int $id): ?array
	{
		$result = AudienceTypeTable::query()->setSelect([
			'TITLE',
		])
			->where('ID', $id)
			->fetch();

		if ($result === null)
		{
			return null;
		}

		return $result;
	}

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['TITLE'] = '';
		return $result;
	}

	public static function deleteById(int $id): void
	{
		$relatedCouples = CoupleTable::query()
			->setSelect(['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME'])
			->where('UP_SCHEDULE_AUDIENCE.AUDIENCE_TYPE_ID', $id)
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE', AudienceTable::class, Join::on('this.AUDIENCE_ID', 'ref.ID')
				)))
			->fetchCollection();
		foreach ($relatedCouples as $couple)
		{
			$couple->delete();
		}
		AudienceTypeTable::delete($id);
	}

	public static function add(array $data): void
	{
		$audienceType = new EO_AudienceType();
		if (($title = $data['TITLE']) !== null)
		{
			$audienceType->setTitle($title);
			$audienceType->save();
		}
		else
		{
			throw new \Exception();
		}
	}

	public static function editById(int $id, ?array $data): void
	{
		$type = AudienceTypeTable::getByPrimary($id)->fetchObject();

	/*	echo "<pre>";
		var_dump($data);
		var_dump($type); die;*/
		if ($data['TITLE'] !== null)
		{
			$type->setTitle($data['TITLE']);
		}
		$type->save();
		// TODO: handle exceptions
	}

	public static function getArrayOfRelatedEntitiesById(int $id): ?array
	{
		$relatedEntities = [];
		$relatedCouples = CoupleTable::query()
			->setSelect(['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME'])
			->where('UP_SCHEDULE_AUDIENCE.AUDIENCE_TYPE_ID', $id)
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE', AudienceTable::class, Join::on('this.AUDIENCE_ID', 'ref.ID')
				)))
			->fetchAll();
		if(!empty($relatedCouples))
		{
			$relatedEntities['COUPLES'] = $relatedCouples;
		}
		return $relatedEntities;
	}
	public static function getAllArray(): ?array
	{
		return AudienceTypeTable::query()
			->setSelect(['ID', 'TITLE',])
			->fetchAll();
	}

	public static function getAll(): ?EO_AudienceType_Collection
	{
		return AudienceTypeTable::query()
			->setSelect(['ID', 'TITLE'])
			->fetchCollection();
	}

	public static function getByTitle(string $title): ?EO_AudienceType
	{
		return AudienceTypeTable::query()
			->setSelect(['ID', 'TITLE'])
			->where('TITLE', $title)
			->fetchObject();
	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$DB->Query('TRUNCATE TABLE up_schedule_audience_type');
		return $DB->GetErrorSQL();
	}
}
