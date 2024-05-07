<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Up\Schedule\Exception\AddEntityException;
use Up\Schedule\Exception\EditEntityException;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;

class AudienceTypeRepository
{
	public static function getArrayForAdminById(int $id): ?array
	{
		$result = AudienceTypeTable::query()
			->setSelect([
				'TITLE',
			])
			->where('ID', $id)
			->fetch();

		return $result ?? null;
	}

	public static function getArrayForAdding($data = []): array
	{
		$result = [];
		$result['TITLE'] = $data['TITLE'] ?? '';

		return $result;
	}

	public static function deleteById(int $id): void
	{
		CoupleRepository::deleteByAudienceTypeId($id);
		SubjectRepository::deleteByAudienceTypeId($id);
		AudienceRepository::deleteByAudienceTypeId($id);

		AudienceTypeTable::delete($id);
	}

	/**
	 * @throws AddEntityException
	 */
	public static function add(array $data): void
	{
		if (($title = $data['TITLE']) === null)
		{
			throw new AddEntityException('Введите название типа аудитории');
		}

		$audienceType = new EO_AudienceType();
		$audienceType->setTitle($title);
		$result = $audienceType->save();

		if(!$result->isSuccess())
		{
			throw new AddEntityException(
				implode(
					'<br>', $result->getErrorMessages()
				)
			);
		}
	}

	/**
	 * @throws EditEntityException
	 * @throws ObjectPropertyException
	 * @throws ArgumentException
	 * @throws SystemException
	 */
	public static function editById(int $id, ?array $data): void
	{
		if ($id === 0)
		{
			throw new EditEntityException('Введите тип аудитории для редактирования');
		}

		$type = AudienceTypeTable::getByPrimary($id)
			->fetchObject();

		if($data['TITLE'])
		{
			$type->setTitle($data['TITLE']);
		}

		$result = $type->save();

		if(!$result->isSuccess())
		{
			throw new EditEntityException(implode('<br>', $result->getErrorMessages()));
		}
	}

	public static function getArrayOfRelatedEntitiesById(int $id): array
	{
		$relatedEntities = [];

		$relatedCouples = CoupleRepository::getArrayByAudienceTypeId($id);
		if (!empty($relatedCouples))
		{
			$relatedEntities['COUPLES'] = $relatedCouples;
		}

		$relatedSubjects = SubjectRepository::getArrayByAudienceTypeId($id);
		if (!empty($relatedSubjects))
		{
			$relatedEntities['SUBJECTS'] = $relatedSubjects;
		}

		$relatedAudiences = AudienceRepository::getArrayByAudienceTypeId($id);
		if (!empty($relatedAudiences))
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
		if ($pageNumber > 1)
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
			->whereLike(
				'TITLE',
				"%$searchInput%"
			)
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
