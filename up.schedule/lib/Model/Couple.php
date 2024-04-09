<?php
namespace Up\Schedule\Model;

use Bitrix\Main\EO_User;
use Bitrix\Main\UserTable;
use Up\Schedule\Repository\UserRepository;

class Couple
{
	public EO_User $teacher;

	public function __construct(
		public EO_Group $group,
		public EO_Subject $subject)
	{
		$this->teacher = UserRepository::getTeacherBySubjectId($subject->getId());
	}
}