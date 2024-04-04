<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Couple_Collection;

class CoupleRepository
{
	public static function getByGroupId(int $groupId): ?EO_Couple_Collection
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY', 'TEACHER'])
			->where('GROUP_ID', $groupId)
			->fetchCollection();
	}
}
