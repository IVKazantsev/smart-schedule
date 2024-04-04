<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;

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
	 */
	public static function getTableName(): string
	{
		return 'up_schedule_group_subject';
	}

	/**
	 * Returns entity map definition.
	 */
	public static function getMap(): array
	{
		return [
			new IntegerField(
				'SUBJECT_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('GROUP_SUBJECT_ENTITY_SUBJECT_ID_FIELD')
				]
			),
			(new Reference(
				'SUBJECT',
				SubjectTable::class,
				Join::on('this.SUBJECT_ID', 'ref.ID')
			))->configureJoinType('inner'),
			new IntegerField(
				'GROUP_ID',
				[
					'primary' => true,
					'title' => Loc::getMessage('GROUP_SUBJECT_ENTITY_GROUP_ID_FIELD')
				]
			),
			(new Reference(
				'GROUP',
				GroupTable::class,
				Join::on('this.GROUP_ID', 'ref.ID')
			))->configureJoinType('inner'),
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
