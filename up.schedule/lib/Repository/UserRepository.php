<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\EO_User;
use Bitrix\Main\EO_User_Collection;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\RoleTable;
use Up\Schedule\Model\SubjectTeacherTable;

class UserRepository
{
	public static function getAll(): ?EO_User_Collection
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
												 'GROUP' => 'UP_SCHEDULE_GROUP.TITLE',
											 ])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
			))
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_GROUP', GroupTable::class, Join::on('this.UF_GROUP_ID', 'ref.ID')
			))
		)->fetchCollection();
	}

	public static function getAllArray(): ?array
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
												 'GROUP' => 'UP_SCHEDULE_GROUP.TITLE',
											 ])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
			))
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_GROUP', GroupTable::class, Join::on('this.UF_GROUP_ID', 'ref.ID')
			))
		)->fetchAll();
	}

	public static function getById(int $id): ?EO_User
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
												 'GROUP' => 'UP_SCHEDULE_GROUP.TITLE',
											 ])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
			))
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_GROUP', GroupTable::class, Join::on('this.UF_GROUP_ID', 'ref.ID')
			))
		)->where('ID', $id)->fetchObject();
	}

	public static function getArrayById(int $id): ?array
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
												 'GROUP' => 'UP_SCHEDULE_GROUP.TITLE',
											 ])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
			))
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_GROUP', GroupTable::class, Join::on('this.UF_GROUP_ID', 'ref.ID')
			))
		)->where('ID', $id)->fetch();
	}

	public static function getTeacherBySubjectId(int $subjectId): EO_User_Collection
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
												 'SUBJECT_ID' => 'UP_SCHEDULE_SUBJECT_TEACHER.SUBJECT_ID',
											 ])->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
				))
			)->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_SUBJECT_TEACHER', SubjectTeacherTable::class, Join::on('this.ID', 'ref.TEACHER_ID')
				))
			)->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_GROUP', GroupTable::class, Join::on('this.UF_GROUP_ID', 'ref.ID')
				))
			)->where('ROLE', 'Преподаватель')->where('SUBJECT_ID', $subjectId)->fetchCollection();
	}

	public static function getAllTeachers(): EO_User_Collection
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE_ID' => 'UP_SCHEDULE_ROLE.ID',
											 ])->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
			))
		)->where('ROLE_ID', 2)->fetchCollection();

		/*return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
											 ])
						->registerRuntimeField((new Reference(
							'UP_SCHEDULE_ROLE',
							RoleTable::class,
							Join::on('this.UF_ROLE_ID', 'ref.ID')
						)))
						->where('ROLE', 'Преподаватель')
						->fetchCollection();*/
	}
}
