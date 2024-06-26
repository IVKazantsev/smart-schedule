<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Query\Query;
use Up\Schedule\Exception\AddEntityException;
use Up\Schedule\Exception\EditEntityException;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Group_Collection;
use Up\Schedule\Model\GroupTable;

class GroupRepository
{
	public static function getAll(): ?EO_Group_Collection
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->fetchCollection();
	}

	public static function getByTitle(string $title): ?EO_Group
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE'])->where('TITLE', $title)->fetchObject();
	}

	public static function getAllArray(): array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getPageWithArrays(int $entityPerPage, int $pageNumber, string $searchInput): array
	{
		$offset = 0;
		if ($pageNumber > 1)
		{
			$offset = $entityPerPage * ($pageNumber - 1);
		}

		return GroupTable::query()->setSelect(['ID', 'TITLE'])->whereLike('TITLE', "%$searchInput%")->setLimit(
				$entityPerPage + 1
			)->setOffset($offset)->setOrder('ID')->fetchAll();
	}

	public static function getCountOfEntities(string $searchInput): int
	{
		$result = GroupTable::query()->addSelect(Query::expr()->count('ID'), 'CNT')->whereLike(
				'TITLE',
				"%$searchInput%"
			)->exec();

		return $result->fetch()['CNT'];
	}

	public static function getById(int $id): ?EO_Group
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->where('ID', $id)->fetchObject();
	}

	public static function getArrayById(int $id): ?array
	{
		$result = GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->where('ID', $id)->fetch();
		if (!$result)
		{
			return null;
		}

		return $result;
	}

	public static function getArrayOfGroupsBySubjectId(int $id): array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->where('SUBJECTS.ID', $id)->fetchAll();
	}

	public static function getArrayForAdminById(int $id): array
	{
		$data = [];
		$group = self::getById($id);

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

	public static function getArrayForAdding($data = []): array
	{
		$result = [];
		$result['TITLE'] = $data['TITLE'] ?? '';
		$subjects = SubjectRepository::getAll();
		foreach ($subjects as $subject)
		{
			$result['SUBJECTS']['ALL_SUBJECTS'][$subject->getId()] = $subject->getTitle();
		}

		if ($data['SUBJECTS_TO_ADD'])
		{
			$currentSubjects = SubjectRepository::getByIds($data['SUBJECTS_TO_ADD']);
			foreach ($currentSubjects as $subject)
			{
				$result['SUBJECTS']['CURRENT_SUBJECTS'][$subject->getId()] = $subject->getTitle();
			}
		}

		return $result;
	}

	/**
	 * @throws AddEntityException
	 */
	public static function add(array $data): void
	{
		if (($title = $data['TITLE']) === null)
		{
			throw new AddEntityException(GetMessage('EMPTY_TITLE'));
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

		$result = $group->save();
		if (!$result->isSuccess())
		{
			throw new AddEntityException(implode('<br>', $result->getErrorMessages()));
		}
	}

	/**
	 * @throws EditEntityException
	 */
	public static function editById(int $id, array $data): void
	{
		if ($id === 0)
		{
			throw new EditEntityException(GetMessage('EMPTY_EDIT_GROUP'));
		}

		$group = self::getById($id);

		if ($data['TITLE'])
		{
			$group?->setTitle($data['TITLE']);
		}

		if (!empty($data['SUBJECTS_TO_DELETE']))
		{
			$couplesCollection = CoupleRepository::getByGroupId($id);
			foreach ($data['SUBJECTS_TO_DELETE'] as $subjectId)
			{
				$group?->getSubjects()->removeByPrimary($subjectId);

				foreach ($couplesCollection as $couple)
				{
					if ($couple->getSubjectId() !== $subjectId)
					{
						continue;
					}

					$couple->delete();
				}
			}
		}

		$subjectsToAdd = SubjectRepository::getByIds($data['SUBJECTS_TO_ADD']);
		if ($subjectsToAdd !== null)
		{
			foreach ($subjectsToAdd as $subject)
			{
				$group?->addToSubjects($subject);
			}
		}

		$result = $group?->save();
		if (!$result->isSuccess())
		{
			throw new EditEntityException(implode('<br>', $result->getErrorMessages()));
		}
	}

	public static function deleteById(int $id): void
	{
		$relatedCouples = CoupleTable::query()->setSelect([
															  'SUBJECT.TITLE',
															  'AUDIENCE.NUMBER',
															  'GROUP.TITLE',
															  'TEACHER.NAME',
															  'TEACHER.LAST_NAME',
														  ])->where('GROUP_ID', $id)->fetchCollection();

		foreach ($relatedCouples as $couple)
		{
			$couple->delete();
		}

		GroupTable::delete($id);

		//TODO: handle exceptions
	}

	public static function getArrayOfRelatedEntitiesById(int $id): array
	{
		$relatedEntities = [];

		$relatedCouples = CoupleTable::query()->setSelect([
															  'SUBJECT.TITLE',
															  'AUDIENCE.NUMBER',
															  'GROUP.TITLE',
															  'TEACHER.NAME',
															  'TEACHER.LAST_NAME',
														  ])->where('GROUP_ID', $id)->fetchAll();

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
		$DB->Query('DELETE FROM up_schedule_group');
		$DB->Query('DELETE FROM up_schedule_group_subject');

		return $DB->GetErrorSQL();
	}
}
