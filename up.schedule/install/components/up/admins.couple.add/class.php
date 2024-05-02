<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\UserRepository;
use Up\Schedule\Service\CoupleService;
use Up\Schedule\Service\EntityService;

class AdminsCoupleAddComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		if(!EntityService::isCurrentUserAdmin())
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
}
