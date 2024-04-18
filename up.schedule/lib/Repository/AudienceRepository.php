<?php

namespace Up\Schedule\Repository;


use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Schedule\Model\AudienceTable;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\EO_Audience;
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

	public static function getById(int $id): ?EO_Audience
	{
		return AudienceTable::query()->setSelect(['ID', 'NUMBER', 'AUDIENCE_TYPE'])->where('ID', $id)->fetchObject();
	}

	public static function getArrayById(int $id): ?array
	{
		return AudienceTable::query()->setSelect([
			'ID',
			'NUMBER',
			'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE'
												 ])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_AUDIENCE_TYPE', AudienceTypeTable::class, Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
			))
		)->where('ID', $id)->fetch();
	}
}
