<?php

namespace Up\Schedule\AutomaticSchedule;

use Bitrix\Main\Entity\Query;
use Bitrix\Main\EO_User_Collection;
use Bitrix\Main\ORM\Objectify\Collection;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Model\EO_Group_Collection;
use Up\Schedule\Model\GroupSubjectTable;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class GeneticSchedule
{
	private int $populationSize = 100; // Размер популяции. Можно увеличить.

	private int $percentageOfSelection = 50;
	private int $maxGenerations = 5000; // Макс. кол-во итерации.

	private int $mutationRate = 10; // Процент мутации
	public readonly EO_Group_Collection $groups;
	public readonly EO_User_Collection $teachers;
	public readonly EO_Audience_Collection $audiences;
	private static array $pricesOfPenalty = [
		'couple-group' => 150,
		'couple-teacher' => 120,
		'couple-audience' => 120,
		'overfulfilment' => 100,
		'underfulfilment' => 120,
		'big_spaces' => 10
//TODO:нагрузка на день
	];

	//private array $currentPopulation;

	/**
	 * @param Collection[] $parameters [EO_Group_Collection $groups, EO_User_Collection $teachers, EO_Audience_Collection $audiences]
	 */
	public function __construct(array $parameters = [])
	{
		try
		{
			$this->assignParameters($parameters);
		}
		catch (\InvalidArgumentException)
		{
			$this->groups = GroupRepository::getAll();
			$this->audiences = AudienceRepository::getAll();
			$this->teachers = UserRepository::getAllTeachers();
		}

		//$this->currentPopulation = $this->createPopulation($this->populationSize);
	}

	/**
	 * @param array $parameters
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	private function assignParameters(array $parameters): void
	{
		if (empty($parameters))
		{
			throw new \InvalidArgumentException();
		}

		foreach ($parameters as $parameter)
		{
			switch (get_class($parameter))
			{
				case 'Up\Schedule\Model\EO_Group_Collection':
					$groups = $parameter;
					break;
				case 'Bitrix\Main\EO_User_Collection':
					$teachers = $parameter;
					break;
				case 'Up\Schedule\Model\EO_Audience_Collection':
					$audiences = $parameter;
					break;
				default:
					throw new \InvalidArgumentException();
			}
		}
		$this->groups = $groups;
		$this->audiences = $audiences;
		$this->teachers = $teachers;
	}

	// Функция для создания первой популяции
	public function createPopulation($populationSize): array
	{
		$population = [];
		for ($i = 0; $i < $populationSize; $i++)
		{
			$population[] = new GeneticPerson($this->groups, $this->audiences, $this->teachers);
		}

		return $population;
	}

	// Функция приспособленности (evaluation function)
	public function fitness(
		GeneticPerson $schedule,
	)
	{
		// 1.Накладки для учебных групп +   		10 баллов штрафа
		// 2.Отсутствие накладок для аудиторий +
		// 3.Отсутствие накладок для преподавателей +
		// 4. Большие окна для группы +
		// 5. Число проведнных пар не превышает заданное +
		// 6. Соответствие типа аудитории виду занятия
		// 7.Необходимость проведения пар в полном объеме запланированных часов +
		$penalty = 0;
		$newSchedule = clone $schedule;
		$couples = $newSchedule->couples->getAll();
		$groups = $this->groups;
		$audiences = $this->audiences;
		$teachers = $this->teachers;

		/*foreach ($groupsId as $item) {
			echo $item . "\n";
		}*/
		//echo count($groupsId) . "count of groups";

		/*foreach ($groups as $group)
		{
			$couplesOnWeekForGroup[$group->getId()][$i][$j] = 0;
		}
		foreach ($teachers as $teacher)
		{
			$couplesOnWeekForTeacher[$teacher->getId()][$i][$j] = 0;
		}
		foreach ($audiences as $audience)
		{
			$couplesOnWeekForAudience[$audience->getId()][$i][$j] = 0;
		}*/
		foreach ($groups as $group)
		{
			$amountsOfCouples[$group->getId()] = count($group->getSubjects()->getIdList());
		}
// TODO: use array_fill
		for ($i = 1; $i <= 6; $i++)
		{
			for ($j = 1; $j <= 7; $j++)
			{
				foreach ($groups as $group)
				{
					$couplesOnWeekForGroup[$group->getId()][$i][$j] = 0;
				}
				foreach ($teachers as $teacher)
				{
					$couplesOnWeekForTeacher[$teacher->getId()][$i][$j] = 0;
				}
				foreach ($audiences as $audience)
				{
					$couplesOnWeekForAudience[$audience->getId()][$i][$j] = 0;
				}
			}
		}



		foreach ($couples as $couple)
		{
			$amountsOfCouples[$couple->getGroupId()]--;
			$couplesOnWeekForGroup[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]++;
			$couplesOnWeekForAudience[$couple->getAudienceId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]++;
			$couplesOnWeekForTeacher[$couple->getTeacherId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]++;

			if (($couplesOnWeekForGroup[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]) > 1)
			{
				$penalty += self::$pricesOfPenalty['couple-group']; //Штраф за накладку по парам у группы
			}
			if (($couplesOnWeekForTeacher[$couple->getTeacherId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]) > 1)
			{
				$penalty += self::$pricesOfPenalty['couple-teacher']; //Штраф за накладку по парам у преподавателей
			}
			if (($couplesOnWeekForAudience[$couple->getAudienceId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]) > 1)
			{
				$penalty += self::$pricesOfPenalty['couple-audience']; //Штраф за накладку по аудиториям
			}
		}
		foreach ($amountsOfCouples as $amountOfCouples)
		{
			if ($amountOfCouples > 0)
			{
				$penalty += abs($amountOfCouples) * self::$pricesOfPenalty['underfulfilment']; //Штраф за недовыполнение учебного плана
			}
			elseif ($amountOfCouples < 0)
			{
				$penalty += abs($amountOfCouples) * self::$pricesOfPenalty['overfulfilment']; //Штраф за перевыполнение учебного плана
			}
		}

//		foreach ($groups as $group)
//		{
//			echo "Group: " . ($group->getTitle()) . "\n";
//			for ($j = 1; $j <= 7; $j++)
//			{
//				for ($i = 1; $i <= 6; $i++)
//				{
//					echo "{$couplesOnWeekForGroup[1/*$group->getId()*/][$i][$j]} | ";
//				}
//				echo "\n";
//			}
//		}
		foreach ($groups->getIdList() as $id)
		{
			for ($i = 1; $i <= 6; $i++)
			{
				while (true)
				{
					$numberOfCouple = array_search(1, $couplesOnWeekForGroup[$id][$i], true); // TODO: change first parameter
					unset($couplesOnWeekForGroup[$id][$i][$numberOfCouple]);

					$numberOfNextCouple = array_search(1, $couplesOnWeekForGroup[$id][$i], true);
					if ($numberOfNextCouple === false)
					{
						break;
					}

					$spaceBetweenCouples = $numberOfNextCouple - $numberOfCouple - 1;
					if ($spaceBetweenCouples > 3) // TODO: хранить расстояние в отдельном поле
					{
						$penalty += self::$pricesOfPenalty['big_spaces']; // Штраф за большие окна между парами (4 и более)
					}
				}
			}
		}

//		return $penalty;
//		echo "\n\n";
//
//		echo "COUNT PM: " . $countPM . "\t" . "COUNT MO: " . $countMO . "\n\n";
//		echo "Приматы:";
//		foreach ($couples as $couple)
//		{
//			if ($couple->getGroup()->getId() === 1)
//			{
//				var_dump(
//					"Group:\t" . $couple->getGroup()->getTitle(),
//					"Subject:\t" . $couple->getSubject()->getTitle(),
//					"Audience:\t" . $couple->getAudience()->getNumber(),
//					"Day|Number:\t" . $couple->getWeekDay() . '|' . $couple->getCoupleNumberInDay(),
//					"Teacher:\t" . $couple->getTeacher()->getLastName() . "\n",
//				);
//			}
//		}
//		echo "Матобы:";
//		foreach ($couples as $couple)
//		{
//			if ($couple->getGroup()->getId() === 2)
//			{
//				var_dump(
//					"Group:\t" . $couple->getGroup()->getTitle(),
//					"Subject:\t" . $couple->getSubject()->getTitle(),
//					"Audience:\t" . $couple->getAudience()->getNumber(),
//					"Day|Number:\t" . $couple->getWeekDay() . '|' . $couple->getCoupleNumberInDay(),
//					"Teacher:\t" . $couple->getTeacher()->getLastName() . "\n",
//				);
//			}
//		}
		$schedule->setFitness($penalty);
	}

	/**
	 * @param GeneticPerson[] $schedules
	 * @return GeneticPerson[]
	 */
	public function selection(array $schedules): array
	{
		uasort($schedules, static function (GeneticPerson $schedule1, GeneticPerson $schedule2) {
			if ($schedule1->getFitness() === $schedule2->getFitness())
			{
				return 0;
			}

			return ($schedule1->getFitness() > $schedule2->getFitness()) ? 1 : -1;
		});

		$schedules = array_slice(
			$schedules,
			0,
			round($this->populationSize * ($this->percentageOfSelection / 100)));

		if ($schedules[0]->getFitness() < 0) // TODO:Вынести в отдельный метод
		{
			return [$schedules[0]];
		}

		return $schedules;
	}

	// Функция скрещивания (crossover)
	public function crossover(GeneticPerson $schedule1, GeneticPerson $schedule2): GeneticPerson
	{
		// Применить оператор скрещивания для создания нового расписания
		// Вернуть новое расписание
		$couplesOfFirstSchedule = $schedule1->couples->getAll();
		$couplesOfSecondSchedule = $schedule2->couples->getAll();
		$groups = $this->groups;
		$amountOfGroups = count($groups->getAll());
		$randAmountOfGroups = random_int(1, $amountOfGroups);

		$firstCouples = [];
		$secondCouples = [];
		// TODO: Предусмотреть, что в расписании могут быть не выставлены некоторые пары


		if ($randAmountOfGroups === 1)
		{
			$randGroupsId = [array_rand($groups->getIdList(), $randAmountOfGroups)];
		}
		else
		{
			$randGroupsId = array_rand($groups->getIdList(), $randAmountOfGroups);
		}

		$newCouples = new EO_Couple_Collection();
		foreach ($randGroupsId as $groupId)
		{
			$couples = [];
			foreach ($couplesOfFirstSchedule as $couple)
			{
				if ($couple->getGroup()->getTitle() === $groups->getByPrimary($groupId + 1)->getTitle())
				{
//					echo "\n\n\nПервоеРасписание: \t" . "\nНазвание: " . $couple->getSubject()->getTitle() .
//						"\n Время(день\номер): ". $couple->getWeekDay() . "\\" . $couple->getCoupleNumberInDay() . "\n\n\n";
					$couples[] = $couple;
//					echo "groupId: " . $groupId;
//					echo $couple->getSubject()->getTitle() . "\n";
				}
			}
			$firstCouples[$groupId] = $couples;
			$couples = [];
			foreach ($couplesOfSecondSchedule as $couple)
			{
				if ($couple->getGroup()->getTitle() === $groups->getByPrimary($groupId + 1)->getTitle())
				{
//					echo
//						"\nВтороеРасписание: \t" . "\nНазвание: " . $couple->getSubject()->getTitle() .
//						"\n Время(день\номер): ". $couple->getWeekDay() . "\\" . $couple->getCoupleNumberInDay() . "\n\n\n";
					$couples[] = $couple;
					//$secondCouples[$groupId][] = $couple;
				}
			}
			$secondCouples[$groupId] = $couples;

			if (count($firstCouples[$groupId]) === 0)
			{
				return $schedule2;
			}

			if (count($secondCouples[$groupId]) === 0)
			{
				return $schedule1;
			}

//			echo "\n\n\n\n\ncrossover: " . $groups->getByPrimary($groupId + 1)->getTitle()  . "\n";
			//$amountOfCouples = $groups->getByPrimary($groupId + 1)->getSubjects()->count();
			$amountOfCouples = min(count($secondCouples[$groupId]), count($firstCouples[$groupId]));
			//echo "amount: " . count($secondCouples[$groupId]) . "\n";
			$randAmountOfChanges = random_int(1, $amountOfCouples);
			echo "\ncount" . count($secondCouples[$groupId]) . "\trand amount of changes: " . $randAmountOfChanges . "\tamount of couples" . $amountOfCouples . "\t" . "\tfit: " . $schedule1->getFitness() .
			"\tfit2: " . $schedule2->getFitness() . "\n";
			//echo "\namount: " . $randAmountOfChanges . "\n";
			if ($randAmountOfChanges === 1)
			{
				$randCouplesId = [array_rand($secondCouples[$groupId])];
			}
			elseif ($randAmountOfChanges < 1)
			{
				$randCouplesId = [];
			}
			else
			{
				$randCouplesId = array_rand($secondCouples[$groupId], $randAmountOfChanges);
			}

			$remainingId = array_diff(array_keys($secondCouples[$groupId]), $randCouplesId);
			//echo "\n" . implode(' - ',$remainingId) . "\n";
			foreach ($randCouplesId as $coupleId)
			{
				$couple = $secondCouples[$groupId][$coupleId]; //Пары берутся из второго расписания
//				echo "\n Название: " . $couple->getSubject()->getTitle()
//				. "\n Время(день\номер): ". $couple->getWeekDay() . "\\" . $couple->getCoupleNumberInDay();
				$newCouples->add($couple);
			}
			foreach ($remainingId as $coupleId)
			{
				$couple = $firstCouples[$groupId][$coupleId]; //Пары берутся из первого расписания
				if ($couple !== null)
				{
					$newCouples->add($couple);
				}
			}
		}
		$newSchedule = clone $schedule1;
		$newSchedule->setCouples($newCouples);
		return $newSchedule;
	}

	// Функция мутации (mutation)
	public function mutate($schedule)
	{
		// Применить оператор мутации для изменения расписания
		// Вернуть измененное расписание
	}

	// Генетический алгоритм для составления расписания
	public function geneticAlgorithm($generations)
	{
//		for($i = 0; $i < 2; $i++)
//		{
//			$schedules[] = new GeneticPerson($groups, $audiences, $teachers);
//			$fit = $alg->fitness($schedules[$i]);
//			$schedules[$i]->setFitness($fit);
//			echo "$i итерация: " . $fit . "\n\n\n";
//			if ($fit > 0) $count++;
//		}
//		$selectedSchedules = $alg->selection($schedules);
//		$i = 0;
//		foreach ($selectedSchedules as $selectedSchedule) {
//			$i++;
//			echo "\nfitness of $i schedule: " . $selectedSchedule->getFitness();
//		}
//		echo "\nКоличество расписаний с накладками: $count";
		$population = $this->createPopulation($this->populationSize);

		for ($i = 0; $i < $generations; $i++)
		{
			// Оценить приспособленность каждого расписания
			array_map([$this, 'fitness'], $population);

			// Выбрать лучшие индивиды (отбор)
			// Например, можно выбрать лучшие 50% расписаний

			$selectedSchedules = $this->selection($population);
			if (count($selectedSchedules) === 1)
			{
				return $selectedSchedules[0];
			}
			// Скрещивание
			$newPopulation = $selectedSchedules;
			while (count($newPopulation) < $this->populationSize)
			{
				$parent1 = $population[array_rand($selectedSchedules)]; //TODO: предусмотреть фиксированный маскимум детей
				$parent2 = $population[array_rand($selectedSchedules)];


//				foreach ($parent1 as $couple)
//				{
//					if ($couple->getGroup()->getTitle() === $groups->getByPrimary($groupId + 1)->getTitle())
//					{
//						echo "\n\n\nПервоеРасписание: \t" . "\nНазвание: " . $couple->getSubject()->getTitle() .
//							"\n Время(день\номер): ". $couple->getWeekDay() . "\\" . $couple->getCoupleNumberInDay() . "\n\n\n";
//						$couples[] = $couple;
////					echo "groupId: " . $groupId;
////					echo $couple->getSubject()->getTitle() . "\n";
//					}
//				}
//				$firstCouples[$groupId] = $couples;
//				$couples = [];
//				foreach ($parent2 as $couple)
//				{
//					if ($couple->getGroup()->getTitle() === $groups->getByPrimary($groupId + 1)->getTitle())
//					{
//						echo
//							"\nВтороеРасписание: \t" . "\nНазвание: " . $couple->getSubject()->getTitle() .
//							"\n Время(день\номер): ". $couple->getWeekDay() . "\\" . $couple->getCoupleNumberInDay() . "\n\n\n";
//						$couples[] = $couple;
//						//$secondCouples[$groupId][] = $couple;
//					}
//				}
//				$secondCouples[$groupId] = $couples;


				$child = $this->crossover($parent1, $parent2);
				$newPopulation[] = $child;
			}

			// Мутация
			// foreach ($newPopulation as &$individual)
			// {
			// 	if (mt_rand(0, 100) < $mutationRate)
			// 	{
			// 		$individual = mutate($individual);
			// 	}
			// }

			// Замена старой популяции новой
			$population = $newPopulation;
		}


		// Найти и вернуть лучшее расписание
		$this->fitness($population[0]);
		$bestSchedule = $population[0];
		foreach ($population as $schedule)
		{
			$this->fitness($schedule);
			if ($schedule->getFitness() < $bestSchedule->getFitness())
			{
				$bestSchedule = $schedule;
			}
		}
//		echo "\n\n\n";
		return $bestSchedule;
	}


}
