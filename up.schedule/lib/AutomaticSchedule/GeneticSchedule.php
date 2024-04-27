<?php

namespace Up\Schedule\AutomaticSchedule;

use Bitrix\Main\Entity\Query;
use Bitrix\Main\EO_User_Collection;
use Bitrix\Main\ORM\Objectify\Collection;
use InvalidArgumentException;
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
		'big_spaces' => 10,
		'difference_between_min_and_max' => 10,
	];


	/**
	 * @param Collection[] $parameters [EO_Group_Collection $groups, EO_User_Collection $teachers, EO_Audience_Collection $audiences]
	 */
	public function __construct(array $parameters = [])
	{
		try
		{
			$this->assignParameters($parameters);
		}
		catch (InvalidArgumentException)
		{
			$this->groups = GroupRepository::getAll();
			$this->audiences = AudienceRepository::getAll();
			$this->teachers = UserRepository::getAllTeachers();
		}
	}

	/**
	 * @param array $parameters
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 */
	private function assignParameters(array $parameters): void
	{
		if (count($parameters) !== 3)
		{
			throw new InvalidArgumentException();
		}

		foreach ($parameters as $parameter)
		{
			switch (get_class($parameter))
			{
				case EO_Group_Collection::class:
					$groups = $parameter;
					break;
				case EO_User_Collection::class:
					$teachers = $parameter;
					break;
				case EO_Audience_Collection::class:
					$audiences = $parameter;
					break;
				default:
					throw new InvalidArgumentException();
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
	public function fitness(GeneticPerson $schedule): void
	{
		// 1.Накладки для учебных групп +
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

		foreach ($groups as $group)
		{
			$amountsOfCouples[$group->getId()] = count($group->getSubjects()->getIdList());
		}
		foreach ($groups as $group)
		{
			$couplesOnWeekForGroup[$group->getId()] = array_fill(1, 6, [array_fill(1, 7, 0)]);
		}
		foreach ($teachers as $teacher)
		{
			$couplesOnWeekForTeacher[$teacher->getId()] = array_fill(1, 6, [array_fill(1, 7, 0)]);
		}
		foreach ($audiences as $audience)
		{
			$couplesOnWeekForAudience[$audience->getId()]= array_fill(1, 6, [array_fill(1, 7, 0)]);
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
			$minAmount = 7;
			$maxAmount = 0;
			$amountOfCouplesInDay = array_fill(1, 6, 0);
			for ($i = 1; $i <= 6; $i++)
			{
				$amountOfCouplesInDay[$i] = count($couplesOnWeekForGroup[$id][$i]);
				$minAmount = ($minAmount > $amountOfCouplesInDay[$i]) ? $amountOfCouplesInDay[$i] : $minAmount;
				$maxAmount = ($maxAmount < $amountOfCouplesInDay[$i]) ? $amountOfCouplesInDay[$i] : $maxAmount;

				while (true)
				{
					$keys = array_keys($couplesOnWeekForGroup[$id][$i]);

					sort($keys);
					$numberOfCouple = array_shift($keys);
					unset($couplesOnWeekForGroup[$id][$i][$numberOfCouple]);

					$numberOfNextCouple = array_shift($keys);

					if ($numberOfNextCouple === null)
					{
						break;
					}

					$spaceBetweenCouples = $numberOfNextCouple - $numberOfCouple - 1;
					if ($spaceBetweenCouples > 0) // TODO: хранить расстояние в отдельном поле
					{
						$penalty += self::$pricesOfPenalty['big_spaces'] * $spaceBetweenCouples; // Штраф за большие окна между парами (4 и более)
					}
				}
			}
			$penalty += self::$pricesOfPenalty['difference_between_min_and_max'] * ($maxAmount - $minAmount);
		}

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
			round($this->populationSize * ($this->percentageOfSelection / 100))
		);

		if ($schedules[0]->getFitness() < 30) // TODO:Вынести в отдельный метод
		{
			return [$schedules[0]];
		}

		return $schedules;
	}

	// Функция скрещивания (crossover)
	public function crossover(GeneticPerson $schedule1, GeneticPerson $schedule2): GeneticPerson
	{
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
			//TODO:Исправить индексацию
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
					$couples[] = $couple;
				}
			}
			$firstCouples[$groupId] = $couples;
			$couples = [];
			foreach ($couplesOfSecondSchedule as $couple)
			{
				if ($couple->getGroup()->getTitle() === $groups->getByPrimary($groupId + 1)->getTitle())
				{
					$couples[] = $couple;
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

			$amountOfCouples = min(count($secondCouples[$groupId]), count($firstCouples[$groupId]));

			$randAmountOfChanges = random_int(1, $amountOfCouples);
			/*echo "\ncount" . count($secondCouples[$groupId]) . "\trand amount of changes: " . $randAmountOfChanges . "\tamount of couples" . $amountOfCouples . "\t" . "\tfit: " . $schedule1->getFitness() .
			"\tfit2: " . $schedule2->getFitness() . "\n";*/ // ВЫВОД
			if ($randAmountOfChanges === 1)
			{
				$randCouplesId = [array_rand($secondCouples[$groupId])];
			}
			else
			{
				$randCouplesId = array_rand($secondCouples[$groupId], $randAmountOfChanges);
			}

			$remainingId = array_diff(array_keys($secondCouples[$groupId]), $randCouplesId);
			foreach ($randCouplesId as $coupleId)
			{
				$couple = $secondCouples[$groupId][$coupleId]; //Пары берутся из второго расписания
				if ($couple !== null)
				{
					$newCouples->add($couple);
				}
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
		$population = $this->createPopulation($this->populationSize);

		for ($i = 0; $i < $generations; $i++)
		{
			// Оценить приспособленность каждого расписания
			array_map([$this, 'fitness'], $population);

			// Выбрать лучшие индивиды (отбор)

			$selectedSchedules = $this->selection($population);
			if (count($selectedSchedules) === 1)
			{
				echo $selectedSchedules[0]->getFitness();
				return $selectedSchedules[0];
			}
			// Скрещивание
			$newPopulation = $selectedSchedules;
			while (count($newPopulation) < $this->populationSize)
			{
				$parent1 = $population[array_rand($selectedSchedules)]; //TODO: предусмотреть фиксированный маскимум детей
				$parent2 = $population[array_rand($selectedSchedules)];


				$child = $this->crossover($parent1, $parent2);
				$newPopulation[] = $child;
			}

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
		//echo $bestSchedule->getFitness();
		return $bestSchedule;
	}
}
