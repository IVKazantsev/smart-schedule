<?php

namespace Up\Schedule\Repository;


use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;
use Up\Schedule\Model\EO_Couple_Collection;

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

	public static function getArrayForAdminById(int $id): ?array
	{
		$result = AudienceTable::query()->setSelect([
			'NUMBER',
			'TYPE' => 'AUDIENCE_TYPE.TITLE',
			])
			->where('ID', $id)
			->fetch();

		if ($result === null)
		{
			return null;
		}

		$result['TYPE'] = array_unique(
			array_merge_recursive(
				[$result['TYPE']],
				array_column(AudienceTypeRepository::getAllArray(), 'TITLE')
			)
		);

		return $result;
	}

	public static function add(array $data): void
	{
		$audience = new EO_Audience();
		if (($number = $data['NUMBER']) !== null && ($type = $data['TYPE']) !== null)
		{
			$audience->setNumber($number);
			$typeEntityObject = AudienceTypeTable::query()
				->setSelect(['ID'])
				->where('TITLE', $type)
				->fetchObject();
			$audience->setAudienceType($typeEntityObject);
			$audience->save();
		}
		else
		{
			throw new \Exception();
		}
	}

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['NUMBER'] = '';
		$result['TYPE'] = array_column(AudienceTypeRepository::getAllArray(), 'TITLE');
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
		$relatedCouples = CoupleTable::query()->setSelect(['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME'])
									 ->where('AUDIENCE_ID', $id)->fetchCollection();
		foreach ($relatedCouples as $couple)
		{
			$couple->delete();
		}
		AudienceTable::delete($id);

		//TODO: handle exceptions
	}

	public static function getArrayOfRelatedEntitiesById(int $id): ?array
	{
		$relatedEntities = [];
		$relatedCouples = CoupleTable::query()
									 ->setSelect(['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME'])
									 ->where('AUDIENCE_ID', $id)
									 ->fetchAll();
		if(!empty($relatedCouples))
		{
			$relatedEntities['COUPLES'] = $relatedCouples;
		}
		return $relatedEntities;
		// TODO: handle exceptions
	}

	public static function getAudiencesBySubjectId(int $id): ?EO_Audience_Collection
	{
		$subject = SubjectRepository::getArrayById($id);
		$audienceTypeId = $subject['AUDIENCE_TYPE_ID'];
		return AudienceTable::query()
			->setSelect(['ID', 'NUMBER', 'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE'])
			->where('AUDIENCE_TYPE_ID', $audienceTypeId)
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
				)))
			->fetchCollection();
	}

	public static function getArrayOfAudiencesBySubjectId(int $id): ?array
	{
		$subject = SubjectRepository::getArrayById($id);
		$audienceTypeId = $subject['AUDIENCE_TYPE_ID'];
		return AudienceTable::query()
			->setSelect(['ID', 'NUMBER', 'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE'])
			->where('AUDIENCE_TYPE_ID', $audienceTypeId)
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
				)))
			->fetchAll();
	}
//	public static function getArrayOfAudiencesBySubjectsId(array $id): ?array
//	{
//		$subjects = SubjectRepository::getArrayByIds($id);
//
//		/*$audiencesTypeId = $subject['AUDIENCE_TYPE_ID'];*/
//		return AudienceTable::query()
//			->setSelect(['ID', 'NUMBER', 'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE'])
//			->setGroup([''])
//			->whereIn('AUDIENCE_TYPE_ID', $audiencesTypeId)
//			->registerRuntimeField(
//				(new Reference(
//					'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
//				)))
//			->fetchAll();
//	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$DB->Query('TRUNCATE TABLE up_schedule_audience');
		return $DB->GetErrorSQL();
	}
}
