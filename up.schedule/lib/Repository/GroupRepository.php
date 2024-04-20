<?php

namespace Up\Schedule\Repository;

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
		return GroupTable::query()
			->setSelect(['ID', 'TITLE', ])
			->where('TITLE', $title)
			->fetchObject();
	}

	public static function getAllArray(): ?array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getById(int $id): ?EO_Group
	{
		return GroupTable::query()
			->setSelect(['ID', 'TITLE'])
			->where('ID', $id)
			->fetchObject();
	}

/*	public static function getArrayById(int $id): ?array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE'])->where('ID', $id)->fetch();
	}*/

	public static function getArrayForAdminById(int $id): ?array
	{
		return GroupTable::query()
			->setSelect(['TITLE'])
			->where('ID', $id)
			->fetch();
	}

	public static function editById(int $id, array $data): void
	{
		$group = GroupTable::getByPrimary($id)->fetchObject();

		if ($data['TITLE'] !== null)
		{
			$group->setTitle($data['TITLE']);
		}
		$group->save();
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		//TODO: delete function
	}
}
