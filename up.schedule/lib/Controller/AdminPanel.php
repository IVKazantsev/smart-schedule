<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class AdminPanel extends Controller
{
	public function getSubjectsListAction(): ?array
	{
		$entityList = SubjectRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}

	public function getUsersListAction(): ?array
	{
		$entityList = UserRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}

	public function getGroupsListAction(): ?array
	{
		$entityList = GroupRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}

	public function getAudiencesListAction(): ?array
	{
		$entityList = AudienceRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}
}