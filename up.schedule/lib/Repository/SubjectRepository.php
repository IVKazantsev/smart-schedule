<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Up\Schedule\Model\AudienceTypeTable;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\SubjectTable;

class SubjectRepository
{
	public static function getAll(): ?EO_Subject_Collection
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchCollection();
	}

	public static function getAllArray(): ?array
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
	}

	public static function getArrayById(int $id): ?array
	{
		return SubjectTable::query()->setSelect(['ID', 'TITLE'])->where('ID', $id)->fetch();
	}

	public static function getArrayForAdminById(int $id): ?array
	{
		$subject = SubjectTable::query()
			->setSelect([
				'ID',
				'TITLE',
				'TYPE' => 'UP_SCHEDULE_AUDIENCE_TYPE.TITLE',
			])
			->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_AUDIENCE_TYPE',
					AudienceTypeTable::class,
					Join::on('this.AUDIENCE_TYPE_ID', 'ref.ID')
				)))
			->where('ID', $id)
			->fetch();

		$subject['TYPE'] = array_unique(
			array_merge_recursive(
				[$subject['TYPE']],
				array_column(AudienceRepository::getAllTypes(), 'TITLE')
			)
		);

		return $subject;
	}

	public static function editById(int $id, array $data): void
	{
		$subject = SubjectTable::getByPrimary($id)->fetchObject();
		$type = AudienceTypeTable::query()
			->setSelect(['ID'])
			->where('TITLE', $data['TYPE'])
			->fetchObject();
		if ($data['TITLE'] !== null)
		{
			$subject->setTitle($data['TITLE']);
		}
		$subject
			->setAudienceType($type)
			->save();
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		//TODO: delete function
	}
}
