<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

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
	 */
	public static function getTableName(): string
	{
		return 'up_schedule_audience';
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
			(new Reference(
				'AUDIENCE_TYPE',
				AudienceTypeTable::class,
				Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))->configureJoinType('inner'),
		];
	}

	/**
	 * Returns validators for NUMBER field.
	 */
	public static function validateNumber(): array
	{
		return [
			new LengthValidator(null, 10),
		];
	}
}
