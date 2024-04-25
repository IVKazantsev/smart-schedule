<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\UserTable;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\SubjectTable;

class CoupleRepository
{
	public static function getByGroupId(int $groupId): ?EO_Couple_Collection
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY', 'TEACHER'])
			->where('GROUP_ID', $groupId)
			->fetchCollection();
	}

	public static function addCouple(array $data): void
	{
		(new EO_Couple())
			->setGroup(GroupTable::getByPrimary($data['GROUP_ID'])->fetchObject())
			->setSubject(SubjectTable::getByPrimary($data['SUBJECT_ID'])->fetchObject())
			->setTeacher(UserTable::getByPrimary($data['TEACHER_ID'])->fetchObject())
			->setAudience(AudienceTable::getByPrimary($data['AUDIENCE_ID'])->fetchObject())
			->setWeekDay($data['DAY_OF_WEEK'])
			->setCoupleNumberInDay($data['NUMBER_IN_DAY'])
			->save();
	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$DB->Query('DELETE FROM up_schedule_couple');
		return $DB->GetErrorSQL();
	}
}
