<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

Loc::loadMessages(__FILE__);

/**
 * Class SubjectTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> TITLE string(255) mandatory
 * <li> AUDIENCE_TYPE_ID int mandatory
 * </ul>
 *
 * @package Up\Schedule
 **/

class SubjectTable extends DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'up_schedule_subject';
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
					'title' => Loc::getMessage('SUBJECT_ENTITY_ID_FIELD')
				]
			),
			new StringField(
				'TITLE',
				[
					'required' => true,
					'validation' => [__CLASS__, 'validateTitle'],
					'title' => Loc::getMessage('SUBJECT_ENTITY_TITLE_FIELD')
				]
			),
			new IntegerField(
				'AUDIENCE_TYPE_ID',
				[
					'required' => true,
					'title' => Loc::getMessage('SUBJECT_ENTITY_AUDIENCE_TYPE_ID_FIELD')
				]
			),
			(new Reference(
				'AUDIENCE_TYPE',
				AudienceTypeTable::class,
				Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))->configureJoinType('inner'),
			(new ManyToMany(
				'GROUPS',
				GroupTable::class
			))->configureTableName('up_schedule_group_subject'),
		];
	}

	/**
	 * Returns validators for TITLE field.
	 *
	 * @return array
	 */
	public static function validateTitle()
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}
