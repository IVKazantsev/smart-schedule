<?php

namespace Up\Schedule\AutomaticSchedule;

use Bitrix\Main\Entity\Query;
use Up\Schedule\Model\GroupSubjectTable;
use Up\Schedule\Model\GroupTable;

class GeneticSchedule
{
	private int $populationSize = 200; // Размер популяции. Можно увеличить.

	private int $maxGenerations = 5000; // Макс. кол-во итерации.

	private int $mutationRate = 10; // Процент мутации

	// Функция для создания случайной особи
	function createIndividual(): ?GeneticPerson
	{
		// if (!$this->groups)
		// {
		// 	$groups = GroupTable::query()->setSelect(['ID', 'TITLE'])->fetchAll();
		// }
		// else
		// {
		// 	$groups = $this->groups;
		// }
		//
		// if ($groups)
		// {
		// 	$group = array_rand($groups);
		// }
		// else
		// {
		// 	return null;
		// }
		//
		// $subjectsForGroup = GroupSubjectTable::query()->setSelect(['SUBJECT_ID', 'HOURS_NUMBER'])->where(
		// 		'GROUP_ID',
		// 		$group['ID']
		// 	)->fetchAll();
		// if ($subjectsForGroup)
		// {
		// 	$subject = array_rand($subjectsForGroup);
		// }
		// else
		// {
		// 	return null;
		// }
		//
		// return new GeneticPerson($group, $subject);
	}

	// Функция для создания первой популяции
	function createPopulation($populationSize)
	{
		$population = [];
		for ($i = 0; $i < $populationSize; $i++)
		{
			$population[] = $this->createIndividual();
		}

		return $population;
	}

	// Функция приспособленности (evaluation function)
	function fitness($schedule)
	{

	}

	// Функция скрещивания (crossover)
	function crossover($schedule1, $schedule2)
	{
		// Применить оператор скрещивания для создания нового расписания
		// Вернуть новое расписание
	}

	// Функция мутации (mutation)
	function mutate($schedule)
	{
		// Применить оператор мутации для изменения расписания
		// Вернуть измененное расписание
	}

	// Генетический алгоритм для составления расписания
	function geneticAlgorithm($populationSize, $generations)
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