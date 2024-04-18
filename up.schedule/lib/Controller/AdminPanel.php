<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class AdminPanel extends Controller
{
	public function getSubjectListAction(): ?array
	{
		$entityList = SubjectRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}

	public function getUserListAction(): ?array
	{
		$entityList = UserRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}

	public function getGroupListAction(): ?array
	{
		$entityList = GroupRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}

	public function getAudienceListAction(): ?array
	{
		$entityList = AudienceRepository::getAllArray();
		return [
			'entityList' => $entityList,
		];
	}
}