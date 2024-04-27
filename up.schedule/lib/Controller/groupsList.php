<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\Controller;
use Up\Schedule\Repository\GroupRepository;

class GroupesList extends Controller
{
	public function getGroupListAction(): ?array
	{
		return [
			'groups' => $this->getGroupsData(),
			/*'currentGroup' => $this->getCurrentGroup(),*/
		];
	}

	private function getGroupsData(): array
	{
		return GroupRepository::getAllArray();
	}

	/*private function getCurrentGroup(): array
	{
		$currentGroupId = (int)request()->get('groupId');
		return GroupRepository::getArrayById($currentGroupId) ? : [];
	}*/
}
