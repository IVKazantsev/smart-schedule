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
use Bitrix\Main\UserTable;

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
	 */
	public static function getTableName(): string
	{
		return 'up_schedule_subject';
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
			(new ManyToMany(
				'USERS',
				UserTable::class
			))->configureTableName('up_schedule_subject_teacher'),
		];
	}

	/**
	 * Returns validators for TITLE field.
	 */
	public static function validateTitle(): array
	{
		return [
			new LengthValidator(null, 255),
		];
	}
}
