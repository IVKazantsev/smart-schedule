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
		$user = UserTable::query()->setSelect(['ID',
											   'NAME',
											   'LAST_NAME',
											   'EMAIL',
											   'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
											   'GROUP' => 'UP_SCHEDULE_GROUP.TITLE'])
								  ->registerRuntimeField(
									  (new Reference(
										  'UP_SCHEDULE_ROLE',
										  RoleTable::class,
										  Join::on('this.UF_ROLE_ID', 'ref.ID')
									  )))
								  ->registerRuntimeField(
									  (new Reference(
										  'UP_SCHEDULE_GROUP',
										  GroupTable::class,
										  Join::on('this.UF_GROUP_ID', 'ref.ID')
									  )))
								  ->where('ID', $id)
								  ->fetch();
		return $user;
	}
}
