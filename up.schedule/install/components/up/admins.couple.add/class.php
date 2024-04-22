<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\UserRepository;

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
		$data = [];
		$teachers = [];
		$audiences = [];
		$subjectId = (int)$this->arParams['SUBJECT_ID'];
		foreach (UserRepository::getTeachersBySubjectId($subjectId) as $teacher)
		{
			$teachers[] = [
				'ID' => $teacher->getId(),
				'TITLE' => $teacher->getName() . " " . $teacher->getLastName()
			];
		}
		foreach (AudienceRepository::getAudiencesBySubjectId($subjectId) as $audience)
		{
			$audiences[] = [
				'ID' => $audience->getId(),
				'TITLE' => $audience->getNumber(),
			];
		}

		$data['TEACHERS'] = $teachers;
		$data['AUDIENCES'] = $audiences;
		$data['DAYS_OF_WEEK'] = [
			1 => "Понедельник",
			2 => "Вторник",
			3 => "Среда",
			4 => "Четверг",
			5 => "Пятница",
			6 => "Суббота",
		];
		$data['NUMBER_IN_DAY'] = [
			1 => "Первая пара",
			2 => "Вторая пара",
			3 => "Третья пара",
			4 => "Четвертая пара",
			5 => "Пятая пара",
			6 => "Шестая пара",
			7 => "Седьмая пара",
		];
		return $data;
	}

	protected function checkRole(): bool
	{
		return CurrentUser::get()->isAdmin();
	}
}
