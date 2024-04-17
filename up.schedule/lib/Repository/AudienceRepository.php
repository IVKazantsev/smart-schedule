<?php

namespace Up\Schedule\Repository;


use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\EO_Audience_Collection;

class AudienceRepository
{
	public static function getAll(): ?EO_Audience_Collection
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->fetchCollection();
	}

	public static function getAllArray(): ?array
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->fetchAll();
	}
}
