<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\CoupleService;

class AdminsCoupleAddComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!$this->checkRole())
		{
			LocalRedirect('/404/');
		}
		$this->arResult['DATA'] = $this->getCoupleInfo();
		$this->includeComponentTemplate();
	}

	private function getCoupleInfo(): ?array
	{
		$subjectId = (int)$this->arParams['SUBJECT_ID'];
		return CoupleService::getCoupleDataBySubjectId($subjectId);
	}

	protected function checkRole(): bool
	{
		return CurrentUser::get()->isAdmin();
	}
}
