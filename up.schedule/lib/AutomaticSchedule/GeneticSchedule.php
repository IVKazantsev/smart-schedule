<?php

namespace Up\Schedule\AutomaticSchedule;

use Bitrix\Main\Entity\Query;
use Up\Schedule\Model\GroupSubjectTable;
use Up\Schedule\Model\GroupTable;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class GeneticSchedule
{
	private int $populationSize = 200; // Размер популяции. Можно увеличить.

	private int $maxGenerations = 5000; // Макс. кол-во итерации.

	private int $mutationRate = 10; // Процент мутации

	public function __construct()
	{
		$this->createPopulation($this->populationSize);
	}

	// Функция для создания первой популяции
	public function createPopulation($populationSize): array
	{
		$groups = GroupRepository::getAll();
		$audiences = AudienceRepository::getAll();
		$teachers = UserRepository::getAllTeachers();
		$subjects = SubjectRepository::getAll();

		$population = [];
		for ($i = 0; $i < $populationSize; $i++)
		{
			$population[] = new GeneticPerson($groups, $audiences, $teachers, $subjects);
		}

		return $population;
	}

	// Функция приспособленности (evaluation function)
	public function fitness($schedule)
	{

	}

	// Функция скрещивания (crossover)
	public function crossover($schedule1, $schedule2)
	{
		// Применить оператор скрещивания для создания нового расписания
		// Вернуть новое расписание
	}

	// Функция мутации (mutation)
	public function mutate($schedule)
	{
		// Применить оператор мутации для изменения расписания
		// Вернуть измененное расписание
	}

	// Генетический алгоритм для составления расписания
	public function geneticAlgorithm($populationSize, $generations)
	{
		$population = initializePopulation($populationSize);

		for ($i = 0; $i < $generations; $i++)
		{
			// Оценить приспособленность каждого расписания
			$fitnessScores = array_map("fitness", $population);

			// Выбрать лучшие индивиды (отбор)
			// Например, можно выбрать лучшие 50% расписаний
			$selectedIndexes = array_keys($fitnessScores, max($fitnessScores));

			// Скрещивание
			$newPopulation = [];
			while (count($newPopulation) < $populationSize)
			{
				$parent1 = $population[array_rand($selectedIndexes)];
				$parent2 = $population[array_rand($selectedIndexes)];
				$child = crossover($parent1, $parent2);
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
		return $population[array_search(max($fitnessScores), $fitnessScores)];
	}
}