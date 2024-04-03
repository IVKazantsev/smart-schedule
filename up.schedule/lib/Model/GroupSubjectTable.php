<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class GroupSubjectTable
 *
 * Fields:
 * <ul>
 * <li> SUBJECT_ID int mandatory
 * <li> GROUP_ID int mandatory
 * <li> HOURS_NUMBER int mandatory
 * </ul>
 *
 * @package Up\Schedule
 **/

class GroupSubjectTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_schedule_group_subject';
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
				'SUBJECT_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('GROUP_SUBJECT_ENTITY_SUBJECT_ID_FIELD')
				]
			),
			new IntegerField(
				'GROUP_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('GROUP_SUBJECT_ENTITY_GROUP_ID_FIELD')
				]
			),
			new IntegerField(
				'HOURS_NUMBER',
				[
					'required' => true,
					'title' => Loc::getMessage('GROUP_SUBJECT_ENTITY_HOURS_NUMBER_FIELD')
				]
			),
		];
	}
}
