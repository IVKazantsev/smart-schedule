<?php

namespace Up\Schedule\Repository;

use Bitrix\Main\DB\Exception;
use Bitrix\Main\EO_User;
use Bitrix\Main\EO_User_Collection;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\UserTable;
use CUser;
use Up\Schedule\Model\CoupleTable;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_SubjectTeacher;
use Up\Schedule\Model\EO_SubjectTeacher_Collection;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Model\RoleTable;
use Up\Schedule\Model\SubjectTable;
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

	public static function getAllArray(): array
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

	public static function getPageWithArrays(int $entityPerPage, int $pageNumber, string $searchInput): array
	{
		$offset = 0;
		if ($pageNumber > 1)
		{
			$offset = $entityPerPage * ($pageNumber - 1);
		}

		return UserTable::query()->setSelect([
												 'ID',
												 'LOGIN',
												 'NAME',
												 'LAST_NAME',
												 'EMAIL',
												 'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
											 ])->where(
			Query::filter()->logic('or')->whereLike('NAME', "%$searchInput%")->whereLike(
				'LAST_NAME',
				"%$searchInput%"
			)
		)->registerRuntimeField(
			(new Reference(
				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
			))
		)->setLimit($entityPerPage + 1)->setOffset($offset)->setOrder('ID')->fetchAll();
	}

	public static function getCountOfEntities(string $searchInput): int
	{
		$result = UserTable::query()->addSelect(Query::expr()->count('ID'), 'CNT')->where(
			Query::filter()->logic('or')->whereLike('NAME', "%$searchInput%")->whereLike(
				'LAST_NAME',
				"%$searchInput%"
			)
		)->exec();

		return $result->fetch()['CNT'];
	}

	public static function getById(int $id): ?EO_User
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'LOGIN',
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

	public static function getTeacherById(int $id): ?EO_User
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
		)->where('ID', $id)->where('ROLE', 'Преподаватель')->fetchObject();
	}

	public static function getArrayOfTeacherById(int $id): array|false
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
		)->where('ID', $id)->where('ROLE', 'Преподаватель')->fetch();
	}

	public static function getArrayForAdminById(int $id): ?array
	{
		$user = UserTable::query()->setSelect([
												  'LOGIN',
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
		if($user === false)
		{
			return null;
		}

		$user['PASSWORD'] = '';
		$user['CONFIRM_PASSWORD'] = '';

		$roles = RoleTable::query()->setSelect(['ID', 'TITLE',])->fetchAll();

		$groups = GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
		if ($user['ROLE'] === 'Студент')
		{
			$user['GROUP'] = array_unique(
				array_merge_recursive(
					[$user['GROUP']],
					array_column($groups, 'TITLE')
				)
			);
		}
		else
		{
			unset($user['GROUP']);
		}

		if ($user['ROLE'] === 'Преподаватель')
		{
			foreach (SubjectRepository::getAll() as $subject)
			{
				$user['SUBJECTS']['ALL_SUBJECTS'][$subject->getId()] = $subject->getTitle();
			}
			$subjects = SubjectTeacherTable::query()->setSelect(['SUBJECTS' => 'UP_SCHEDULE_SUBJECT'])->where(
				'TEACHER_ID',
				$id
			)->registerRuntimeField(
				(new Reference(
					'UP_SCHEDULE_SUBJECT', SubjectTable::class, Join::on('this.SUBJECT_ID', 'ref.ID')
				))
			)->fetchAll();
			foreach ($subjects as $subject)
			{
				$user['SUBJECTS']['CURRENT_SUBJECTS'][$subject['SUBJECTSID']] = $subject['SUBJECTSTITLE'];
				unset($user['SUBJECTS']['ALL_SUBJECTS'][$subject['SUBJECTSID']]);
			}
		}

		$user['ROLE'] = array_unique(
			array_merge_recursive(
				[$user['ROLE']],
				array_column($roles, 'TITLE')
			)
		);

		return $user;
	}

	public static function getTeachersBySubjectId(int $subjectId): EO_User_Collection
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

	public static function getArrayOfTeachersBySubjectId(int $subjectId): ?array
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
		)->where('ROLE', 'Преподаватель')->where('SUBJECT_ID', $subjectId)->fetchAll();
	}

	//	public static function getArrayOfTeachersBySubjectsId(array $subjectsId): ?array
	//	{
	//		return UserTable::query()->setSelect([
	//			'ID',
	//			'NAME',
	//			'LAST_NAME',
	//			'EMAIL',
	//			'ROLE' => 'UP_SCHEDULE_ROLE.TITLE',
	//			'SUBJECT_ID' => 'UP_SCHEDULE_SUBJECT_TEACHER.SUBJECT_ID',
	//		])->registerRuntimeField(
	//			(new Reference(
	//				'UP_SCHEDULE_ROLE', RoleTable::class, Join::on('this.UF_ROLE_ID', 'ref.ID')
	//			))
	//		)->registerRuntimeField(
	//			(new Reference(
	//				'UP_SCHEDULE_SUBJECT_TEACHER', SubjectTeacherTable::class, Join::on('this.ID', 'ref.TEACHER_ID')
	//			))
	//		)->registerRuntimeField(
	//			(new Reference(
	//				'UP_SCHEDULE_GROUP', GroupTable::class, Join::on('this.UF_GROUP_ID', 'ref.ID')
	//			))
	//		)
	//			->where('ROLE', 'Преподаватель')
	//			->whereIn('SUBJECT_ID', $subjectsId)
	//			->fetchAll();
	//	}

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
	}

	public static function getAllTeachersArray(): array
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
		)->where('ROLE_ID', 2)->fetchAll();
	}

	public static function getArrayForAdding(): ?array
	{
		$result = [];
		$result['LOGIN'] = '';
		$result['NAME'] = '';
		$result['LAST_NAME'] = '';
		$result['EMAIL'] = '';
		$result['PASSWORD'] = '';
		$result['CONFIRM_PASSWORD'] = '';

		$result['ROLE'] = array_column(RoleRepository::getAllArray(), 'TITLE');

		return $result;
	}

	public static function add(array $data): string
	{
		if($data['LOGIN'] === null)
		{
			return 'Введите логин';
		}
		if($data['EMAIL'] === null)
		{
			return 'Введите почту';
		}
		if($data['NAME'] === null)
		{
			return 'Введите имя';
		}
		if($data['LAST_NAME'] === null)
		{
			return 'Введите фамилию';
		}
		if($data['PASSWORD'] === null)
		{
			return 'Введите пароль';
		}
		if($data['CONFIRM_PASSWORD'] === null)
		{
			return 'Подтвердите пароль';
		}
		if($data['PASSWORD'] !== $data['CONFIRM_PASSWORD'])
		{
			return 'Пароли не совпадают';
		}
		if($data['ROLE'] === null)
		{
			return 'Выберите роль';
		}

		$fields = [];
		$validate = function(string $fieldName, mixed $value) use (&$fields): void {
			if ($value !== null)
			{
				$fields[$fieldName] = $value;
			}
		};

		$validate('LOGIN', $data['LOGIN']);
		$validate('EMAIL', $data['EMAIL']);
		$validate('NAME', $data['NAME']);
		$validate('LAST_NAME', $data['LAST_NAME']);
		$validate('PASSWORD', $data['PASSWORD']);
		$validate('CONFIRM_PASSWORD', $data['CONFIRM_PASSWORD']);
		$validate('UF_ROLE_ID', RoleRepository::getByTitle($data['ROLE'] ?? '')?->getId());

		if ($data['ROLE'] === 'Администратор')
		{
			$group = array(1);
			$fields['GROUP_ID'] = $group;
		}

		$user = new CUser();
		$ID = $user->Add($fields);

		if ((int)$ID <= 0)
		{
			return $user->LAST_ERROR;
		}

		return '';
	}

	public static function getTeacherByFirstAndLastName(string $name, string $lastName): ?EO_User
	{
		return UserTable::query()->setSelect([
												 'ID',
												 'NAME',
												 'LAST_NAME',
											 ])->where('UF_ROLE_ID', 2)->where('NAME', $name)->where(
			'LAST_NAME',
			$lastName
		)->fetchObject();
	}

	public static function editById(int $id, array $data): string
	{
		$fields = [];

		$validate = static function(string $fieldName, mixed $value) use (&$fields): void {
			if ($value !== null)
			{
				$fields[$fieldName] = $value;
			}
		};

		if($id === 0)
		{
			return 'Введите пользователя для редактирования';
		}

		if($data['PASSWORD'] !== 0)
		{
			if($data['PASSWORD'] !== $data['CONFIRM_PASSWORD'])
			{
				return 'Пароли не совпадают';
			}

			$validate('PASSWORD', $data['PASSWORD']);
		}

		$validate('NAME', $data['NAME']);
		$validate('LAST_NAME', $data['LAST_NAME']);
		$validate('EMAIL', $data['EMAIL']);

		if (!array_key_exists('GROUP', $data) || !$data['GROUP'] || $data['ROLE'] !== 'Студент')
		{
			$fields['UF_GROUP_ID'] = null;
		}
		else
		{
			$validate('UF_GROUP_ID', GroupRepository::getByTitle($data['GROUP'])?->getId());
		}

		$validate('UF_ROLE_ID', RoleRepository::getByTitle($data['ROLE'] ?? '')?->getId());

		if ($data['ROLE'] === 'Администратор')
		{
			$group = array(1);
			$fields['GROUP_ID'] = $group;
		}

		$user = new CUser();
		$result = $user->Update($id, $fields);
		if($result === false)
		{
			return 'Не удалось отредактировать пользователя';
		}

		if ($data['ROLE'] === 'Преподаватель')
		{
			foreach ($data['SUBJECTS_TO_DELETE'] as $subjectId)
			{
				$result = SubjectTeacherTable::getByPrimary(['TEACHER_ID' => $id, 'SUBJECT_ID' => $subjectId])
											 ->fetchObject();
				$result?->delete();
			}
			$collection = new EO_SubjectTeacher_Collection();
			foreach ($data['SUBJECTS_TO_ADD'] as $subjectId)
			{
				$subjectTeacherEntity = new EO_SubjectTeacher();
				$subjectTeacherEntity->setSubjectId($subjectId);
				$subjectTeacherEntity->setTeacherId($id);
				$collection->add($subjectTeacherEntity);
			}
			$collection->save();
		}
		else
		{
			SubjectTeacherTable::deleteByFilter(['TEACHER_ID' => $id]);
			CoupleTable::deleteByFilter(['TEACHER_ID' => $id]);
		}

		return '';
		// TODO: handle exceptions
	}

	public static function deleteById(int $id): void
	{
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('TEACHER_ID', $id)->fetchCollection();
		foreach ($relatedCouples as $couple)
		{
			$couple->delete();
		}
		CUser::Delete($id);

		//TODO: handle exceptions
	}

	public static function getArrayOfRelatedEntitiesById(int $id): ?array
	{
		$relatedEntities = [];
		$relatedCouples = CoupleTable::query()->setSelect(
			['SUBJECT.TITLE', 'AUDIENCE.NUMBER', 'GROUP.TITLE', 'TEACHER.NAME', 'TEACHER.LAST_NAME']
		)->where('TEACHER_ID', $id)->fetchAll();
		if (!empty($relatedCouples))
		{
			$relatedEntities['COUPLES'] = $relatedCouples;
		}

		return $relatedEntities;
		// TODO: handle exceptions
	}

	public static function deleteAllFromDB(): string
	{
		global $DB;
		$users = UserTable::query()->setSelect([
												   'ID',
												   'NAME',
												   'LAST_NAME',
											   ])->whereNot('UF_ROLE_ID', 1)->where(
			Query::filter()->logic('or')->whereNotNull('UF_ROLE_ID')->whereNotNull('UF_GROUP_ID')
		)->fetchCollection();

		foreach ($users as $user)
		{
			$result = CUser::Delete($user->getId());
			if (!$result)
			{
				return "Не удалось удалить пользователя {$user->getName()} {$user->getLastName()}";
			}
		}

		$DB->Query("DELETE FROM b_uts_user where UF_ROLE_ID != 1");
		$DB->Query("DELETE FROM up_schedule_subject_teacher");

		return $DB->GetErrorSQL();
	}
}
