<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class CoupleTable
 *
 * Fields:
 * <ul>
 * <li> GROUP_ID int mandatory
 * <li> SUBJECT_ID int mandatory
 * <li> TEACHER_ID int mandatory
 * <li> AUDIENCE_ID int mandatory
 * <li> WEEK_DAY int mandatory
 * <li> COUPLE_NUMBER_IN_DAY int mandatory
 * <li> WEEK_TYPE string(10) optional
 * </ul>
 *
 * @package Up\Schedule
 **/

class CoupleTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_schedule_couple';
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
				'GROUP_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_GROUP_ID_FIELD')
				]
			),
			new IntegerField(
				'SUBJECT_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_SUBJECT_ID_FIELD')
				]
			),
			new IntegerField(
				'TEACHER_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_TEACHER_ID_FIELD')
				]
			),
			new IntegerField(
				'AUDIENCE_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_AUDIENCE_ID_FIELD')
				]
			),
			new IntegerField(
				'WEEK_DAY',
				[
					'required' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_WEEK_DAY_FIELD')
				]
			),
			new IntegerField(
				'COUPLE_NUMBER_IN_DAY',
				[
					'required' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_COUPLE_NUMBER_IN_DAY_FIELD')
				]
			),
			new StringField(
				'WEEK_TYPE',
				[
					'validation' => [__CLASS__, 'validateWeekType'],
					'title' => Loc::getMessage('COUPLE_ENTITY_WEEK_TYPE_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for WEEK_TYPE field.
	 *
	 * @return array
	 */
	public static function validateWeekType()
	{
		return [
			new LengthValidator(null, 10),
		];
	}
}
