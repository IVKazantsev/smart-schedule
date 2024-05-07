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
	private int $populationSize = 50; // Размер популяции. Можно увеличить.

	private int $percentageOfSelection = 50; // Процент особей,"выживающих" при отборе
	private int $maxGenerations = 1000; // Ограничение на максимальное количество поколений(итераций алгоритма)

	private int $limitOfFitness = 150; // Порог функции приспособленности (если ниже этого значения, то расписание пригодно)
	private int $mutationRate = 20; // Процент мутации

	public readonly EO_Group_Collection $groups;
	public readonly EO_User_Collection $teachers;
	public readonly EO_Audience_Collection $audiences;
	private static array $pricesOfPenalty = [
		'couple-group' => 550, // Штраф за накладку по паре у группы
		'couple-teacher' => 550, // Штраф за накладку по паре у преподавателя
		'couple-audience' => 500, // Штраф за накладку по паре у аудитории
		'overfulfilment' => 500, // Недовыполнение плана
		'underfulfilment' => 500, // Перевыполнение плана
		'big_spaces' => 10, // Штраф за большие окна между парами
		'difference_between_min_and_max' => 10, // Штраф за большое расхождение в количестве пар между днями
	];

	/**
	 * @param Collection[] $parameters [EO_Group_Collection $groups, EO_User_Collection $teachers, EO_Audience_Collection $audiences]
	 * Если массив коллекций $parameters не передан, то данные берутся из БД
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
	/**
	 * @param $populationSize
	 * @return GeneticPerson[]
	 */
	public function createPopulation($populationSize): array
	{
		$population = [];
		for ($i = 0; $i < $populationSize; $i++)
		{
			$population[] = new GeneticPerson($this->groups, $this->audiences, $this->teachers);
			$this->fitness($population[$i]);
		}
		$population = $this->sortSchedulesByFitness($population);
		return $population;
	}

	// Функция приспособленности (evaluation function)
	public function fitness(GeneticPerson $schedule): void
	{
		$penalty = 0;
		$couples = $schedule->couples->getAll();
		$groups = $this->groups;
		$audiences = $this->audiences;
		$teachers = $this->teachers;

		foreach ($groups as $group)
		{
			$idOfSubject[$group->getId()] = $group->getSubjects()->getIdList();
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
			if (is_int($index = array_search($couple->getSubjectId(), $idOfSubject[$couple->getGroupId()], true)))
			{
				unset($idOfSubject[$couple->getGroupId()][$index]);
				$amountsOfCouples[$couple->getGroupId()]--;
			}
			$couplesOnWeekForGroup[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]++;
			$couplesOnWeekForAudience[$couple->getAudienceId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]++;
			$couplesOnWeekForTeacher[$couple->getTeacherId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]++;

			if (($couplesOnWeekForGroup[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]) > 1)
			{
				$penalty += self::$pricesOfPenalty['couple-group'];
			}
			if (($couplesOnWeekForTeacher[$couple->getTeacherId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]) > 1)
			{
				$penalty += self::$pricesOfPenalty['couple-teacher'];
			}
			if (($couplesOnWeekForAudience[$couple->getAudienceId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()]) > 1)
			{
				$penalty += self::$pricesOfPenalty['couple-audience'];
			}
		}

		foreach ($amountsOfCouples as $amountOfCouples)
		{
			if ($amountOfCouples > 0)
			{
				$penalty += abs($amountOfCouples) * self::$pricesOfPenalty['underfulfilment'];
			}
			elseif ($amountOfCouples < 0)
			{
				$penalty += abs($amountOfCouples) * self::$pricesOfPenalty['overfulfilment'];
			}
		}

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
					if ($spaceBetweenCouples > 0)
					{
						$penalty += self::$pricesOfPenalty['big_spaces'] * $spaceBetweenCouples;
					}
				}
			}
			$penalty += self::$pricesOfPenalty['difference_between_min_and_max'] * ($maxAmount - $minAmount);
		}

		$schedule->setFitness($penalty);
	}

	public function sortSchedulesByFitness(array $schedules): array
	{
		usort($schedules, static function (GeneticPerson $schedule1, GeneticPerson $schedule2) {
			if ($schedule1->getFitness() === $schedule2->getFitness())
			{
				return 0;
			}

			return ($schedule1->getFitness() > $schedule2->getFitness()) ? 1 : -1;
		});
		return $schedules;
	}
	/**
	 * @param GeneticPerson[] $schedules
	 * @return GeneticPerson[]
	 */
	public function selection(array $schedules): array
	{
		$schedules = $this->sortSchedulesByFitness($schedules);

		$schedules = array_slice(
			$schedules,
			0,
			round($this->populationSize * ($this->percentageOfSelection / 100))
		);
		if ($schedules[0]->getFitness() <= $this->limitOfFitness)
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

		$firstCouples = [];
		$secondCouples = [];

		foreach ($couplesOfFirstSchedule as $couple)
		{
			$firstCouples[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()] = true;
			$fixedArrayOfFirstCouples[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()] = $couple;
		}
		foreach ($couplesOfSecondSchedule as $couple)
		{
			$secondCouples[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()] = true;
			$fixedArrayOfSecondCouples[$couple->getGroupId()][$couple->getSubjectId()] = $couple;
		}

		$availableCouplesOfFirstScheduleToSwitch = [];

		foreach ($firstCouples as $groupId => $couples)
		{
			foreach ($couples as $day => $coupleNumber)
			{
				foreach ($coupleNumber as $numberInDay => $couple)
				{
					if ($couple !== ($secondCouples[$groupId][$day][$numberInDay] ?? false))
					{
						$availableCouplesOfFirstScheduleToSwitch[] = $fixedArrayOfFirstCouples[$groupId][$day][$numberInDay];
					}
				}
			}
		}

		$newCouples = new EO_Couple_Collection();
		$randAmountOfChanges = random_int(0, count($availableCouplesOfFirstScheduleToSwitch));

		for($i = 0; $i < $randAmountOfChanges; $i++)
		{
			$availableCoupleIndex = array_rand($availableCouplesOfFirstScheduleToSwitch, 1);
			$availableCouple = $availableCouplesOfFirstScheduleToSwitch[$availableCoupleIndex];

			$newCouples->add($availableCouple);
			unset(
				$fixedArrayOfSecondCouples[$availableCouple->getGroupId()][$availableCouple->getSubjectId()],
				$availableCouplesOfFirstScheduleToSwitch[$availableCoupleIndex]
			);
		}

		foreach ($fixedArrayOfSecondCouples as $arrayByGroup)
		{
			foreach ($arrayByGroup as $couple)
			{
				$newCouples->add($couple);
			}
		}

		$newSchedule = clone $schedule1;
		$newSchedule->setCouples($newCouples);

		return $newSchedule;
	}

	// Функция мутации (mutation)
	/**
	 * @param GeneticPerson[] $schedules
	 * @return GeneticPerson[]
	 */
	public function mutate(array $schedules): array
	{
		// Применить оператор мутации для изменения расписания
		// Вернуть измененное расписание
		$schedules = $this->sortSchedulesByFitness($schedules);

		$rate = $this->mutationRate;
		$size = $this->populationSize;
		$arrayKeys = array_keys($schedules);
		$arrayKeys = array_slice($arrayKeys, $size/2 - 1);

		$amountOfMutations = round($size * $rate / 100);
		$ids = array_rand($arrayKeys, $amountOfMutations);

		foreach ($ids as $id)
		{
			$this->replaceCouples($schedules[$id]);
			$this->fitness($schedules[$id]);
		}

		return $this->sortSchedulesByFitness($schedules);
	}

	private function replaceCouples(GeneticPerson $schedule): GeneticPerson
	{
		$newSchedule = clone $schedule;
		$couples = $newSchedule->couples->getAll();

		$countOfCouples = $newSchedule->couples->count();
		$groups = $this->groups;

		foreach ($groups as $group)
		{
			$freeCouples[$group->getId()] = array_fill(1, 6, array_fill(1, 7, true));
		}

		foreach ($couples as $couple)
		{
			$freeCouples[$couple->getGroupId()][$couple->getWeekDay()][$couple->getCoupleNumberInDay()] = false;
		}

		$countOfChanges = random_int(0, $countOfCouples);

		for($i = 0; $i < $countOfChanges; $i++)
		{
			$id = array_rand($couples);

			$randDay = random_int(1, 6);

			$day = $freeCouples[$couples[$id]->getGroupId()][$randDay];

			$freeCouplesInDay = array_keys($day, true, true);

			if ($freeCouplesInDay !== [])
			{
				$newNumberInDay = $freeCouplesInDay[array_rand($freeCouplesInDay)];
				$couples[$id]->setCoupleNumberInDay($newNumberInDay);
				$couples[$id]->setWeekDay($randDay);
			}
		}
		$newCollection = new EO_Couple_Collection();
		foreach ($couples as $couple)
		{
			$newCollection->add($couple);
		}

		$newSchedule->setCouples($newCollection);

		return $newSchedule;
	}

	// Генетический алгоритм для составления расписания
	/**
	 * @param GeneticPerson[] $population
	 * @param int $amountOfIterations
	 * @return GeneticPerson[]
	 */
	public function doIterations(array $population, int $amountOfIterations): array
	{
		for ($i = 0; $i < $amountOfIterations; $i++)
		{
			// Оценить приспособленность каждого расписания
			array_map([$this, 'fitness'], $population);


			// Селекция
			$selectedSchedules = $this->selection($population);

			// Проверка условий останова алгоритма
			if (count($selectedSchedules) === 1)
			{
				return [$selectedSchedules[0]];
			}

			// Скрещивание
			$children = [];
			while (count($children) < $this->populationSize / 2)
			{
				$parent1 = $population[array_rand($selectedSchedules)]; //TODO: предусмотреть фиксированный маскимум детей
				$parent2 = $population[array_rand($selectedSchedules)];


				$child = $this->crossover($parent1, $parent2);
				$this->fitness($child);
				$children[] = $child;
			}

			$newPopulation = array_merge($children, $selectedSchedules);

			// Замена старой популяции новой + сортировка по значениям функции приспособленности
			$population = $this->sortSchedulesByFitness($newPopulation);

		}

		return $population;
	}

	public function getPopulationSize(): int
	{
		return $this->populationSize;
	}
	public function getLimitOfFitness(): int
	{
		return $this->limitOfFitness;
	}

	public function getMaxGenerations(): int
	{
		return $this->maxGenerations;
	}
}
