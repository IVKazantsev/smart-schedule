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

	public static function getAllArray(): ?array
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getById(int $id): ?EO_Group
	{
		return GroupTable::query()->setSelect(['ID', 'TITLE'])->where('ID', $id)->fetchObject();
	}
}
