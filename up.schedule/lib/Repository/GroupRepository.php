<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\GroupTable;

class GroupRepository
{
	public static function getAll()
	{
		return GroupTable::getList([
			'select' => [
				'ID',
				'TITLE',
			]
		])->fetchCollection();
	}
	public static function getById(int $id)
	{
		return GroupTable::getList([
			'select' => [
				'ID',
				'TITLE',
			],
			'filter' => ['=ID' => $id],
		])->fetch();
	}
}
