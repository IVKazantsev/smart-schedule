<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\GroupSubjectTable;
use Up\Schedule\Model\SubjectTable;

class SubjectRepository
{
	public static function getAll(): ?EO_Subject_Collection
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchCollection();
	}

	public static function getAllArray(): ?array
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getArrayById(int $id): ?array
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE', 'AUDIENCE_TYPE_ID'])->where('ID', $id)->fetch();
	}

	public static function getByIds(array $id): ?EO_Subject_Collection
	{
		if (empty($id))
		{
			return null;
		}
		return SubjectTable::query()
			->setSelect([
				'TITLE',
				'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE',
				])
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
				)))
			->whereIn('ID', $id)
			->fetchCollection();
	}

	public static function getByTitle(string $title): ?EO_Subject
	{
		return SubjectTable::query()
						   ->setSelect([
										   'TITLE',
										   'AUDIENCE_TYPE.TITLE',
									   ])
						   ->where('TITLE', $title)
						   ->fetchObject();
	}

	public static function getByTitles(array $titles): ?EO_Subject_Collection
	{
		return SubjectTable::query()
						   ->setSelect([
										   'TITLE',
										   'AUDIENCE_TYPE.TITLE',
									   ])
						   ->whereIn('TITLE', $titles)
						   ->fetchCollection();
	}

	public static function getArrayByGroupId(int $id): ?array
	{
		$subjects = GroupSubjectTable::query()
			->setSelect(['SUBJECTS' => 'UP_SCHEDULE_SUBJECT'])
			->where('GROUP_ID', $id)
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_SUBJECT', SubjectTable::class, Join::on('this.SUBJECT_ID', 'ref.ID')
				)))
			->fetchAll();
		return $subjects;
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

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['TITLE'] = '';
		$result['TYPE'] = array_column(AudienceTypeRepository::getAllArray(), 'TITLE');
		return $result;
	}

	public static function add(array $data): void
	{
		$subject = new EO_Subject();
		if (($title = $data['TITLE']) !== null && ($type = $data['TYPE']) !== null)
		{
			$subject->setTitle($title);
			$typeEntityObject = AudienceTypeTable::query()
				->setSelect(['ID'])
				->where('TITLE', $type)
				->fetchObject();
			$subject->setAudienceType($typeEntityObject);
			$subject->save();
		}
		else
		{
			throw new \Exception();
		}
	}

	public static function editById(int $id, array $data): void
	{
		$subject = SubjectTable::getByPrimary($id)->fetchObject();
		$type = AudienceTypeTable::query()->setSelect(['ID'])->where('TITLE', $data['TYPE'])->fetchObject();
		if ($data['TITLE'] !== null)
		{
			$subject->setTitle($data['TITLE']);
		}
		$subject->setAudienceType($type)->save();
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
}
