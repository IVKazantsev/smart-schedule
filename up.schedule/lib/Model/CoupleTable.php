<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField,
	Bitrix\Main\ORM\Fields\StringField,
	Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Data\Internal\DeleteByFilterTrait;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

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
	use DeleteByFilterTrait;

	/**
	 * Returns DB table name for entity.
	 */
	public static function getTableName(): string
	{
		return 'up_schedule_couple';
	}

	/**
	 * Returns entity map definition.
	 */
	public static function getMap(): array
	{
		return [
			new IntegerField(
				'GROUP_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_GROUP_ID_FIELD')
				]
			),
			(new Reference(
				'GROUP',
				GroupTable::class,
				Join::on('this.GROUP_ID', 'ref.ID')
			))->configureJoinType('inner'),
			new IntegerField(
				'SUBJECT_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_SUBJECT_ID_FIELD')
				]
			),
			(new Reference(
				'SUBJECT',
				SubjectTable::class,
				Join::on('this.SUBJECT_ID', 'ref.ID')
			))->configureJoinType('inner'),
			new IntegerField(
				'TEACHER_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_TEACHER_ID_FIELD')
				]
			),
			(new Reference(
				'TEACHER',
				UserTable::class,
				Join::on('this.TEACHER_ID', 'ref.ID')
			))->configureJoinType('inner'),
			new IntegerField(
				'AUDIENCE_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('COUPLE_ENTITY_AUDIENCE_ID_FIELD')
				]
			),
			(new Reference(
				'AUDIENCE',
				AudienceTable::class,
				Join::on('this.AUDIENCE_ID', 'ref.ID')
			))->configureJoinType('inner'),
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
	 */
	public static function validateWeekType(): array
	{
		return [
			new LengthValidator(null, 10),
		];
	}
}
