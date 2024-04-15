<?php

use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\AutomaticSchedule\GeneticPerson;
use Up\Schedule\AutomaticSchedule\GeneticSchedule;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class SidebarComponent extends CBitrixComponent
{
	public function executeComponent(): void
	{
		$this->fetchUserInfo();
		$this->includeComponentTemplate();
	}

	protected function fetchUserInfo(): void
	{
		$userId = CurrentUser::get()->getId();
		$user = UserRepository::getById($userId);
		//
		$groups = GroupRepository::getAll();
		$audiences = AudienceRepository::getAll();
		$teachers = UserRepository::getAllTeachers();
		//echo "<pre>";
		$count = 0;
		$schedules = [];

		/*$alg = new GeneticSchedule([$groups, $teachers, $audiences]);
		echo "<pre>";
		$alg->geneticAlgorithm(200);*/

		/*for($i = 0; $i < 2; $i++)
		{
			$schedules[] = new GeneticPerson($groups, $audiences, $teachers);
			$fit = $alg->fitness($schedules[$i]);
			$schedules[$i]->setFitness($fit);
			echo "$i итерация: " . $fit . "\n\n\n";
			if ($fit > 0) $count++;
		}
		$selectedSchedules = $alg->selection($schedules);
		$i = 0;
		foreach ($selectedSchedules as $selectedSchedule) {
			$i++;
			echo "\nfitness of $i schedule: " . $selectedSchedule->getFitness();
		}
		echo "\nКоличество расписаний с накладками: $count";*/
		//


		if ($user)
		{
			$this->arResult['USER_ROLE'] = $user->get('UP_SCHEDULE_ROLE')?->get('TITLE') ?? 'Гость';
			$this->arResult['USER_NAME'] = $user->getName();
			$this->arResult['USER_LAST_NAME'] = $user->getLastName();

			$this->arResult['IS_AUTHORIZED'] = true;

			return;
		}

		$this->arResult['IS_AUTHORIZED'] = false;
	}
}
