<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Group_Collection;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\SubjectTable;

class GroupRepository
{
	public static function getAll(): ?EO_Group_Collection
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->fetchCollection();
	}

	public static function getByTitle(string $title): ?EO_Group
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE',])->where('TITLE', $title)->fetchObject();
	}

	public static function getAllArray(): ?array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE',])->fetchAll();
	}

	public static function getById(int $id): ?EO_Group
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->where('ID', $id)->fetchObject();
	}

	public static function getArrayById(int $id): ?array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->where('ID', $id)->fetch();
	}

	/*	public static function getArrayById(int $id): ?array
		{
			return GroupTable::query()->setSelect(['ID', 'TITLE'])->where('ID', $id)->fetch();
		}*/

	public static function getArrayForAdminById(int $id): ?array
	{
		/*echo "<pre>";
		$subjects = self::getById($id)?->getSubjects();
		var_dump($subjects);*/
		$data = [];
		$group = self::getById($id);/*GroupTable::query()
			->setSelect(['TITLE', 'SUBJECTS'])
			->where('ID', $id)
			->fetch();*/
		$data['TITLE'] = $group?->getTitle();
		foreach (SubjectRepository::getAll() as $subject)
		{
			$data['SUBJECTS']['ALL_SUBJECTS'][$subject->getId()] = $subject->getTitle();
		}
		foreach ($group?->getSubjects() as $subject)
		{
			$data['SUBJECTS']['CURRENT_SUBJECTS'][$subject->getId()] = $subject->getTitle();
			unset($data['SUBJECTS']['ALL_SUBJECTS'][$subject->getId()]);
		}

		return $data;
	}

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['TITLE'] = '';
		foreach (SubjectRepository::getAll() as $subject)
		{
			$result['SUBJECTS']['ALL_SUBJECTS'][$subject->getId()] = $subject->getTitle();
		}
		$result['SUBJECTS']['CURRENT_SUBJECTS'] = [];
		return $result;
	}

	public static function add(array $data): void
	{
		if (($title = $data['TITLE']) === null)
		{
			throw new \Exception();
		}

		$group = new EO_Group();
		$group->setTitle($title);

		if (($subjectsToAdd = SubjectRepository::getByIds($data['SUBJECTS_TO_ADD'])) !== null)
		{
			foreach ($subjectsToAdd as $subject)
			{
				$group->addToSubjects($subject);
			}
		}
		$group->save();
	}

	public static function editById(int $id, array $data): void
	{
		$group = self::getById($id);
		/*$group = GroupTable::getByPrimary($id)->fetchObject();*/

		if ($data['TITLE'] !== null)
		{
			$group?->setTitle($data['TITLE']);
		}
		foreach ($data['SUBJECTS_TO_DELETE'] as $subjectId)
		{
			$group?->getSubjects()->removeByPrimary($subjectId);
		}
		$subjectsToAdd = SubjectRepository::getByIds($data['SUBJECTS_TO_ADD']);
		if ($subjectsToAdd !== null)
		{
			foreach ($subjectsToAdd as $subject)
			{
				$group?->addToSubjects($subject);
			}
		}
		//die;
		//echo "posle:\n";
		//var_dump($group); die;
		$group?->save();
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('GROUP_ID', $id)->fetchCollection();
		foreach ($relatedCouples as $couple)
		{
			$couple->delete();
		}
		GroupTable::delete($id);

		//TODO: handle exceptions
	}

	public static function getArrayOfRelatedEntitiesById(int $id): ?array
	{
		$relatedEntities = [];
		$relatedCouples = CoupleTable::query()->setSelect(
				['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
			)->where('GROUP_ID', $id)->fetchAll();
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
		$DB->Query('TRUNCATE TABLE up_schedule_group');
		$DB->Query('TRUNCATE TABLE up_schedule_group_subject');
		return $DB->GetErrorSQL();
	}
}
