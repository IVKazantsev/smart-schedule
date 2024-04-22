<?php

use Bitrix\Main\Engine\CurrentUser;

class AdminsSelectGroupComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!$this->checkRole())
		{
			LocalRedirect('/404/');
		}
		$this->arResult['SUBJECTS'] = $this->getSubjectsInfo();
		$this->includeComponentTemplate();
	}

	private function getSubjectsInfo(): ?array
	{
		$id = (int)$this->arParams['GROUP_ID'];
		return \Up\Schedule\Repository\SubjectRepository::getArrayByGroupId($id);
	}

	protected function checkRole(): bool
	{
		return CurrentUser::get()->isAdmin();
	}
}
