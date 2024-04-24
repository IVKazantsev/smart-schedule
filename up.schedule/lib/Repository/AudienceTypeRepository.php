<?php

namespace Up\Schedule\Repository;

use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_AudienceType;
use Up\Schedule\Model\EO_AudienceType_Collection;

class AudienceTypeRepository
{
	public static function getAll(): ?EO_AudienceType_Collection
	{
		return AudienceTypeTable::query()
							->setSelect(['ID', 'TITLE'])
							->fetchCollection();
	}

	public static function getByTitle(string $title): ?EO_AudienceType
	{
		return AudienceTypeTable::query()
								->setSelect(['ID', 'TITLE'])
			->where('TITLE', $title)
								->fetchObject();
	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$DB->Query('TRUNCATE TABLE up_schedule_audience_type');
		return $DB->GetErrorSQL();
	}
}
