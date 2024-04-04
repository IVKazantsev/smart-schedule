<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\CoupleTable;

class CoupleRepository
{
	public static function getByGroupId(int $groupId)
	{
		return CoupleTable::query()
			->setSelect(['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY'])
			->where('GROUP_ID', $groupId)
			->fetchCollection();
	}
}
