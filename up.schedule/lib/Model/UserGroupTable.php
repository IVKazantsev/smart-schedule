<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

Loc::loadMessages(__FILE__);

/**
 * Class UserGroupTable
 *
 * Fields:
 * <ul>
 * <li> USER_ID int mandatory
 * <li> GROUP_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Schedule
 **/

class UserGroupTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_schedule_user_group';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return [
			new IntegerField(
				'USER_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('USER_GROUP_ENTITY_USER_ID_FIELD')
				]
			),
			new IntegerField(
				'GROUP_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('USER_GROUP_ENTITY_GROUP_ID_FIELD')
				]
			),
			(new Reference(
				'USER',
				UserTable::class,
				Join::on('this.USER_ID', 'ref.ID')
			))->configureJoinType('inner'),
			(new Reference(
				'GROUP',
				GroupTable::class,
				Join::on('this.GROUP_ID', 'ref.ID')
			))->configureJoinType('inner'),
		];
	}
}
