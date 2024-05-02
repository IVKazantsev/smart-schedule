<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;
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

	public static function getRoleByUserId(int $id): ?string
	{
		return RoleTable::query()->setSelect([
										  'ID',
										  'TITLE',
									  ])->registerRuntimeField(
			(new Reference(
				'b_uts_user', UserTable::class, Join::on('this.ID', 'ref.UF_ROLE_ID')
			))
		)->where('b_uts_user.VALUE_ID', $id)->getQuery();
	}
}
