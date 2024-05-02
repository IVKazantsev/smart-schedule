<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;
use Up\Schedule\Model\SubjectTable;

class AudienceTypeRepository
{
	public static function getArrayForAdminById(int $id): ?array
	{
		$result = AudienceTypeTable::query()->setSelect([
			'TITLE',
		])
			->where('ID', $id)
			->fetch();

		return $result ?? null;
	}

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['TITLE'] = '';
		return $result;
	}

	public static function deleteById(int $id): void
	{
		CoupleRepository::deleteByAudienceTypeId($id);
		SubjectRepository::deleteByAudienceTypeId($id);
		AudienceRepository::deleteByAudienceTypeId($id);

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

	public static function getArrayOfRelatedEntitiesById(int $id): array
	{
		$relatedEntities = [];

		$relatedCouples = CoupleRepository::getArrayByAudienceTypeId($id);
		if(!empty($relatedCouples))
		{
			$relatedEntities['COUPLES'] = $relatedCouples;
		}

		$relatedSubjects = SubjectRepository::getArrayByAudienceTypeId($id);
		if(!empty($relatedSubjects))
		{
			$relatedEntities['SUBJECTS'] = $relatedSubjects;
		}

		$relatedAudiences = AudienceRepository::getArrayByAudienceTypeId($id);
		if(!empty($relatedAudiences))
		{
			$relatedEntities['AUDIENCES'] = $relatedAudiences;
		}

		return $relatedEntities;
	}
	public static function getAllArray(): array
	{
		return AudienceTypeTable::query()
			->setSelect(['ID', 'TITLE'])
			->fetchAll();
	}

	public static function getPageWithArrays(int $entityPerPage, int $pageNumber, string $searchInput): array
	{
		$offset = 0;
		if($pageNumber > 1)
		{
			$offset = $entityPerPage * ($pageNumber - 1);
		}

		return AudienceTypeTable::query()
			->setSelect(['ID', 'TITLE'])
			->whereLike('TITLE', "%$searchInput%")
			->setLimit($entityPerPage + 1)
			->setOffset($offset)
			->setOrder('ID')
			->fetchAll();
	}

	public static function getCountOfEntities(string $searchInput): int
	{
		$result = AudienceTypeTable::query()
							   ->addSelect(Query::expr()->count('ID'), 'CNT')
							   ->whereLike('TITLE', "%$searchInput%")
							   ->exec();
		return $result->fetch()['CNT'];
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
		$DB->Query('DELETE FROM up_schedule_audience_type');
		return $DB->GetErrorSQL();
	}
}
