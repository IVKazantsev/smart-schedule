<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
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
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->fetchCollection();
	}

	public static function getAllArray(): array
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->fetchAll();
	}

	public static function getPageWithArrays(int $entityPerPage, int $pageNumber, string $searchInput): array
	{
		$offset = 0;
		if ($pageNumber > 1)
		{
			$offset = $entityPerPage * ($pageNumber - 1);
		}

		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->whereLike(
				'NUMBER',
				"%$searchInput%"
			)->setLimit($entityPerPage + 1)->setOffset($offset)->setOrder('ID')->fetchAll();
	}

	public static function getCountOfEntities(string $searchInput): int
	{
		$result = AudienceTable::query()->addSelect(Query::expr()->count('ID'), 'CNT')->whereLike(
				'NUMBER',
				"%$searchInput%"
			)->exec();

		return $result->fetch()['CNT'];
	}

	public static function getById(int $id): ?EO_Audience
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->where('ID', $id)->fetchObject();
	}

	public static function getArrayById(int $id): array|false
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->where('ID', $id)->fetch();
	}

	public static function getArrayForAdminById(int $id): ?array
	{
		$result = AudienceTable::query()->setSelect([
														'NUMBER',
														'TYPE' => 'AUDIENCE_TYPE.TITLE',
													])->where('ID', $id)->fetch();

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

	public static function add(array $data): string
	{
		if (($number = $data['NUMBER']) === null)
		{
			return 'Введите номер аудитории';
		}
		if (($type = $data['TYPE']) === null)
		{
			return 'Выберите тип аудитории';
		}
		$audience = new EO_Audience();

		$audience->setNumber($number);
		$typeEntityObject = AudienceTypeTable::query()->setSelect(['ID'])->where('TITLE', $type)->fetchObject();
		$audience->setAudienceType($typeEntityObject);
		$audience->save();

		return '';
	}

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['NUMBER'] = '';
		$result['TYPE'] = array_column(AudienceTypeRepository::getAllArray(), 'TITLE');

		return $result;
	}

	public static function editById(int $id, array $data): string
	{
		if ($id === 0)
		{
			return 'Введите аудиторию для редактирования';
		}
		if ($data['NUMBER'] === null)
		{
			return 'Введите номер аудитории';
		}
		if ($data['TYPE'] === null)
		{
			return 'Выберите тип аудитории';
		}

		$audience = AudienceTable::getByPrimary($id)->fetchObject();
		$type = AudienceTypeTable::query()->setSelect(['ID'])->where('TITLE', $data['TYPE'])->fetchObject();

		$audience->setNumber($data['NUMBER']);

		if ($audience->getAudienceTypeId() !== $type->getId())
		{
			CoupleTable::deleteByFilter(['AUDIENCE_ID' => $id]);
			$audience->setAudienceType($type);
		}

		$audience->save();
		return '';
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('AUDIENCE_ID', $id)->fetchCollection();
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
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('AUDIENCE_ID', $id)->fetchAll();
		if (!empty($relatedCouples))
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

		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE'])->where(
			'AUDIENCE_TYPE_ID',
			$audienceTypeId
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))
		)->fetchCollection();
	}

	public static function getByNumber(string $number): ?EO_Audience
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE.TITLE'])->where('NUMBER', $number)
							->fetchObject();
	}

	public static function getArrayOfAudiencesBySubjectId(int $id): ?array
	{
		$subject = SubjectRepository::getArrayById($id);
		$audienceTypeId = $subject['AUDIENCE_TYPE_ID'];

		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE'])->where(
			'AUDIENCE_TYPE_ID',
			$audienceTypeId
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))
		)->fetchAll();
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
		$DB->Query('DELETE FROM up_schedule_audience');

		return $DB->GetErrorSQL();
	}

	public static function getArrayByAudienceTypeId(int $id): array
	{
		return AudienceTable::query()->setSelect(['NUMBER'])->where('AUDIENCE_TYPE.ID', $id)->fetchAll();
	}

	public static function deleteByAudienceTypeId(int $id): void
	{
		AudienceTable::deleteByFilter(['AUDIENCE_TYPE_ID' => $id]);
	}
}
