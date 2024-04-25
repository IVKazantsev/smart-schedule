<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\EO_Role;
use Up\Schedule\Model\RoleTable;

class RoleRepository
{
	public static function getByTitle(string $title): ?EO_Role
	{
		return RoleTable::query()
			->setSelect(['ID', 'TITLE', ])
			->where('TITLE', $title)
			->fetchObject();
	}

	public static function getAllArray(): ?array
	{
		return RoleTable::query()
			->setSelect(['ID', 'TITLE'])
			->fetchAll();
	}
}
