<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;

Loc::loadMessages(__FILE__);

/**
 * Class SubjectTeacherTable
 *
 * Fields:
 * <ul>
 * <li> SUBJECT_ID int mandatory
 * <li> TEACHER_ID int mandatory
 * </ul>
 *
 * @package Up\Schedule
 **/

class SubjectTeacherTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_schedule_subject_teacher';
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
					'title' => Loc::getMessage('SUBJECT_TEACHER_ENTITY_SUBJECT_ID_FIELD')
				]
			),
			new IntegerField(
				'TEACHER_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('SUBJECT_TEACHER_ENTITY_TEACHER_ID_FIELD')
				]
			),
		];
	}
}
