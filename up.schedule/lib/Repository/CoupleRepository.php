<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\SubjectTable;

class CoupleRepository
{
	/**
	 * @throws \Exception
	 */
	public static function deleteCouple(array $coupleInfo): void
	{
		$primary = [
			'GROUP_ID' => $coupleInfo['GROUP_ID'],
			'SUBJECT_ID' => $coupleInfo['SUBJECT_ID'],
			'TEACHER_ID' => $coupleInfo['TEACHER_ID'],
			'AUDIENCE_ID' => $coupleInfo['AUDIENCE_ID'],
		];
		CoupleTable::delete($primary);

	}

	public static function getByGroupId(int $groupId): ?EO_Couple_Collection
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY', 'TEACHER'])
			->where('GROUP_ID', $groupId)
			->fetchCollection();
	}

	public static function getArrayByAudienceId(int $audienceId): ?array
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY', 'TEACHER', 'GROUP'])
			->where('AUDIENCE_ID', $audienceId)
			->fetchAll();
	}

	public static function getArrayByTeacherId(int $teacherId): ?array
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY', 'TEACHER', 'GROUP'])
			->where('TEACHER_ID', $teacherId)
			->fetchAll();
	}

	public static function getArrayByGroupId(int $groupId): ?array
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY', 'TEACHER', 'GROUP'])
			->where('GROUP_ID', $groupId)
			->fetchAll();
	}

	public static function addCouple(array $data): string
	{
		$result = (new EO_Couple())
			->setGroup(GroupTable::getByPrimary($data['GROUP_ID'])->fetchObject())
			->setSubject(SubjectTable::getByPrimary($data['SUBJECT_ID'])->fetchObject())
			->setTeacher(UserTable::getByPrimary($data['TEACHER_ID'])->fetchObject())
			->setAudience(AudienceTable::getByPrimary($data['AUDIENCE_ID'])->fetchObject())
			->setWeekDay($data['DAY_OF_WEEK'])
			->setCoupleNumberInDay($data['NUMBER_IN_DAY'])
			->save();

		if(!$result->isSuccess())
		{
			return implode('<br>', $result->getErrorMessages());
		}

		return '';
	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$DB->Query('DELETE FROM up_schedule_couple');
		return $DB->GetErrorSQL();
	}

	public static function getArrayByAudienceTypeId(int $id): array
	{
		return CoupleTable::query()
						  ->setSelect(['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME'])
						  ->where('UP_SCHEDULE_AUDIENCE.AUDIENCE_TYPE_ID', $id)
						  ->registerRuntimeField(
							  (new Reference(
								  'UP_SCHEDULE_AUDIENCE', AudienceTable::class, Join::on('this.AUDIENCE_ID', 'ref.ID')
							  )))
						  ->fetchAll();
	}

	public static function getByDayAndNumber(int $weekDay, int $coupleNumber): ?EO_Couple_Collection
	{
		return CoupleTable::query()
						  ->setSelect(['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME'])
						  ->where('WEEK_DAY', $weekDay)
						  ->where('COUPLE_NUMBER_IN_DAY', $coupleNumber)
						  ->fetchCollection();
	}

	public static function deleteByAudienceTypeId(int $id): void
	{
		global $DB;

		$DB->Query("
			DELETE up_schedule_couple
			FROM up_schedule_couple
				INNER JOIN up_schedule_audience ON up_schedule_couple.AUDIENCE_ID = up_schedule_audience.ID
			WHERE up_schedule_audience.AUDIENCE_TYPE_ID = $id
		");
	}
}
