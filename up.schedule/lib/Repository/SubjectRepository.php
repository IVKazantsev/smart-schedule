<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\UserTable;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\GroupSubjectTable;
use Up\Schedule\Model\SubjectTable;
use Up\Schedule\Model\SubjectTeacherTable;

class SubjectRepository
{
	public static function getAll(): ?EO_Subject_Collection
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchCollection();
	}

	public static function getAllArray(): array
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getPageWithArrays(int $entityPerPage, int $pageNumber, string $searchInput): array
	{
		$offset = 0;
		if ($pageNumber > 1)
		{
			$offset = ($entityPerPage * ($pageNumber - 1));
		}

		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->whereLike('TITLE', "%$searchInput%")->setLimit(
			$entityPerPage + 1
		)->setOffset($offset)->setOrder('ID')->fetchAll();
	}

	public static function getCountOfEntities(string $searchInput): int
	{
		$result = SubjectTable::query()->addSelect(Query::expr()->count('ID'), 'CNT')->whereLike(
			'TITLE',
			"%$searchInput%"
		)->exec();

		return $result->fetch()['CNT'];
	}

	public static function getArrayById(int $id): array|false
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE', 'AUDIENCE_TYPE_ID'])->where('ID', $id)->fetch();
	}

	public static function getByIds(array $id): ?EO_Subject_Collection
	{
		if (empty($id))
		{
			return null;
		}

		return SubjectTable::query()->setSelect([
													'TITLE',
													'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE',
												])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))
		)->whereIn('ID', $id)->fetchCollection();
	}

	public static function getArrayByIds(array $id): ?array
	{
		if (empty($id))
		{
			return null;
		}

		return SubjectTable::query()->setSelect([
													'TITLE',
													'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE',
												])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))
		)->whereIn('ID', $id)->fetchAll();
	}

	public static function getByTitle(string $title): ?EO_Subject
	{
		return SubjectTable::query()->setSelect([
													'TITLE',
													'AUDIENCE_TYPE.TITLE',
												])->where('TITLE', $title)->fetchObject();
	}

	public static function getByTitles(array $titles): ?EO_Subject_Collection
	{
		return SubjectTable::query()->setSelect([
													'TITLE',
													'AUDIENCE_TYPE.TITLE',
												])->whereIn('TITLE', $titles)->fetchCollection();
	}

	public static function getArrayByGroupId(int $id): ?array
	{
		return SubjectTable::query()->setSelect([
													'ID',
													'TITLE',
													'AUDIENCE_TYPE.TITLE',
													'GROUPS',
												])->where('GROUPS.ID', $id)->fetchAll();
	}

	// TODO
	public static function getArrayByAudienceId(int $id): ?array
	{
		return SubjectTable::query()->setSelect([
													'ID',
													'TITLE',
													'AUDIENCE_TYPE.TITLE',
													'GROUPS',
												])->registerRuntimeField(
			new Reference(
				'up_schedule_audience', AudienceTable::class, Join::on('this.AUDIENCE_TYPE.ID', 'ref.AUDIENCE_TYPE_ID')
			)
		)->where('up_schedule_audience.ID', $id)->fetchAll();
	}

	// TODO
	public static function getArrayByTeacherId(int $id): ?array
	{
		return SubjectTable::query()->setSelect([
													'ID',
													'TITLE',
													'AUDIENCE_TYPE.TITLE',
													'GROUPS',
												])->registerRuntimeField(
			new Reference(
				'up_schedule_subject_teacher', SubjectTeacherTable::class, Join::on('this.ID', 'ref.SUBJECT_ID')
			)
		)->where('up_schedule_subject_teacher.TEACHER_ID', $id)->fetchAll();
	}

	public static function getArrayForAdminById(int $id): ?array
	{
		$subject = SubjectTable::query()->setSelect([
														'TITLE',
														'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE',
													])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))
		)->where('ID', $id)->fetch();

		$subject['TYPE'] = array_unique(
			array_merge_recursive(
				[$subject['TYPE']],
				array_column(AudienceTypeRepository::getAllArray(), 'TITLE')
			)
		);

		return $subject;
	}

	public static function getArrayForAdding($data = []): ?array
	{
		$result = [];
		$result['TITLE'] = $data['TITLE'] ?? '';

		$result['TYPE'] = array_unique(
			array_merge_recursive(
				[$data['TYPE']],
				array_column(AudienceTypeRepository::getAllArray(), 'TITLE')
			)
		);
		return $result;
	}

	public static function add(array $data): string
	{
		if (($title = $data['TITLE']) === null)
		{
			return 'Введите название предмета';
		}
		if (($type = $data['TYPE']) === null)
		{
			return 'Выберите тип аудитории';
		}

		$subject = new EO_Subject();
		$subject->setTitle($title);
		$typeEntityObject = AudienceTypeTable::query()->setSelect(['ID'])->where('TITLE', $type)->fetchObject();
		$subject->setAudienceType($typeEntityObject);
		$result = $subject->save();

		if(!$result->isSuccess())
		{
			return implode('<br>', $result->getErrorMessages());
		}

		return '';
	}

	public static function editById(int $id, array $data): string
	{
		if ($id === 0)
		{
			return 'Введите предмет для редактирования';
		}

		$subject = SubjectTable::getByPrimary($id)->fetchObject();
		$type = AudienceTypeTable::query()->setSelect(['ID'])->where('TITLE', $data['TYPE'])->fetchObject();

		if($data['TITLE'])
		{
			$subject->setTitle($data['TITLE']);
		}

		if ($subject->getAudienceTypeId() !== $type->getId())
		{
			CoupleTable::deleteByFilter(['SUBJECT_ID' => $id]);
		}
		$result = $subject->setAudienceType($type)->save();

		if(!$result->isSuccess())
		{
			return implode('<br>', $result->getErrorMessages());
		}

		return '';
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('SUBJECT_ID', $id)->fetchCollection();
		foreach ($relatedCouples as $couple)
		{
			$couple->delete();
		}
		SubjectTable::delete($id);

		//TODO: handle exceptions
	}

	public static function getArrayOfRelatedEntitiesById(int $id): ?array
	{
		$relatedEntities = [];
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('SUBJECT_ID', $id)->fetchAll();
		if (!empty($relatedCouples))
		{
			$relatedEntities['COUPLES'] = $relatedCouples;
		}

		return $relatedEntities;
		// TODO: handle exceptions
	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$DB->Query('DELETE FROM up_schedule_subject');
		$DB->Query('DELETE FROM up_schedule_group_subject');
		$DB->Query('DELETE FROM up_schedule_subject_teacher');

		return $DB->GetErrorSQL();
	}

	public static function getArrayByAudienceTypeId(int $id): array
	{
		return SubjectTable::query()->setSelect(['TITLE'])->where('AUDIENCE_TYPE.ID', $id)->fetchAll();
	}

	public static function getAllByAudienceTypeId(int $id): ?EO_Subject_Collection
	{
		return SubjectTable::query()->setSelect(['TITLE'])->where('AUDIENCE_TYPE_ID', $id)->fetchCollection();
	}

	public static function deleteByAudienceTypeId(int $id): void
	{
		SubjectTable::deleteByFilter(['AUDIENCE_TYPE_ID' => $id]);
	}
}
