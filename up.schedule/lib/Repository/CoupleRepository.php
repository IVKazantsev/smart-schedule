<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\CoupleTable;

class CoupleRepository
{
	public static function getByGroupId(int $groupId)
	{
		return CoupleTable::getList([
			'select' => ['SUBJECT', 'AUDIENCE', 'COUPLE_NUMBER_IN_DAY', 'WEEK_DAY'],
			'filter' => ['=GROUP_ID' => $groupId],
		])->fetchCollection();
	}
}
