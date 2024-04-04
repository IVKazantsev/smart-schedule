<?php
namespace Up\Schedule\Model;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\ORM\Data\DataManager,
	Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

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
	 */
	public static function getTableName(): string
	{
		return 'up_schedule_subject_teacher';
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
					'title' => Loc::getMessage('SUBJECT_TEACHER_ENTITY_SUBJECT_ID_FIELD')
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
					'title' => Loc::getMessage('SUBJECT_TEACHER_ENTITY_TEACHER_ID_FIELD')
				]
			),
			(new Reference(
				'TEACHER',
				UserTable::class,
				Join::on('this.TEACHER_ID', 'ref.ID')
			))->configureJoinType('inner'),
		];
	}
}
