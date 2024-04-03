<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class AudienceTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NUMBER string(10) mandatory
 * <li> AUDIENCE_TYPE_ID int mandatory
 * </ul>
 *
 * @package Up\Schedule
 **/

class AudienceTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_schedule_audience';
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
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('AUDIENCE_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'NUMBER',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateNumber'],
					'title' => Loc::getMessage('AUDIENCE_ENTITY_NUMBER_FIELD')
				]
			),
			new IntegerField(
				'AUDIENCE_TYPE_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('AUDIENCE_ENTITY_AUDIENCE_TYPE_ID_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for NUMBER field.
	 *
	 * @return array
	 */
	public static function validateNumber()
	{
		return [
			new LengthValidator(null, 10),
		];
	}
}
