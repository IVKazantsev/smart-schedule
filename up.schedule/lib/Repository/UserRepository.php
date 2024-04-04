<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\RoleTable;

class UserRepository
{
	public static function getById(int $id)
	{
		$cursor = UserTable::getList([
				'select' => [
					'ID',
					'NAME',
					'LAST_NAME',
					'EMAIL',
					'ROLE' => 'UF_ROLE.TITLE',
					'GROUP' => 'UF_GROUP.TITLE',
				],
				'filter' => ['=ID' => $id],
				'runtime' => [
					(new Reference(
						'UF_ROLE',
						RoleTable::class,
						Join::on('this.UF_ROLE_ID', 'ref.ID')
					))->configureJoinType('inner'),
					(new Reference(
						'UF_GROUP',
						GroupTable::class,
						Join::on('this.UF_GROUP_ID', 'ref.ID')
					))->configureJoinType('inner'),
				]
			]
		);
		$result = $cursor->fetch();
		return $result;
	}
}
