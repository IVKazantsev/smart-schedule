<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\GroupTable;

class GroupRepository
{
	public static function getAll()
	{
		return GroupTable::query()
			->setSelect(['ID', 'TITLE'])
			->fetchCollection();
	}
	public static function getById(int $id)
	{
		return GroupTable::query()
			->setSelect(['ID', 'TITLE'])
			->where('ID', $id)
			->fetch();
	}
}
