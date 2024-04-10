<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\SubjectTable;

class SubjectRepository
{
	public static function getAll(): ?EO_Subject_Collection
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchCollection();
	}
}
