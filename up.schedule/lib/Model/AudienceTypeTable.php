<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);

/**
 * Class AudienceTypeTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> TITLE string(100) mandatory
 * </ul>
 *
 * @package Up\Schedule
 **/

class AudienceTypeTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 */
	public static function getTableName(): string
	{
		return 'up_schedule_audience_type';
	}

	/**
	 * Returns entity map definition.
	 */
	public static function getMap(): array
	{
		return [
			new IntegerField(
				'ID',
				[
					'primary' => true,
					'autocomplete' => true,
					'title' => Loc::getMessage('AUDIENCE_TYPE_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'TITLE',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateTitle'],
					'title' => Loc::getMessage('AUDIENCE_TYPE_ENTITY_TITLE_FIELD')
				]
			),
		];
	}

	/**
	 * Returns validators for TITLE field.
	 */
	public static function validateTitle(): array
	{
		return [
			new LengthValidator(null, 100),
		];
	}
}
