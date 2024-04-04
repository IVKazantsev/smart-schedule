<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class UserTable
 *
 * Fields:
 * <ul>
 * <li> VALUE_ID int mandatory
 * <li> UF_ROLE_ID int optional
 * <li> UF_GROUP_ID int optional
 * </ul>
 *
 * @package Bitrix\Uts
 **/

class UserTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_uts_user';
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
				'VALUE_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('USER_ENTITY_VALUE_ID_FIELD')
				]
			),
			new IntegerField(
				'UF_ROLE_ID',
				[
					'title' => Loc::getMessage('USER_ENTITY_UF_ROLE_ID_FIELD')
				]
			),
			/*(new Reference(
				'UF_ROLE',
				RoleTable::class,
				Join::on('this.UF_ROLE_ID', 'ref.ID')
			))->configureJoinType('inner'),*/
			new IntegerField(
				'UF_GROUP_ID',
				[
					'title' => Loc::getMessage('USER_ENTITY_UF_GROUP_ID_FIELD')
				]
			),
			/*(new Reference(
				'UF_GROUP',
				GroupTable::class,
				Join::on('this.UF_GROUP_ID', 'ref.ID')
			))->configureJoinType('inner'),*/
		];
	}
}
