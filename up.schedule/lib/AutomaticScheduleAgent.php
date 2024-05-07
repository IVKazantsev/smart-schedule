<?php

namespace Up\Schedule;

use Bitrix\Main\Data\Cache;
use Up\Schedule\AutomaticSchedule\GeneticPerson;
use Up\Schedule\AutomaticSchedule\GeneticSchedule;

class AutomaticScheduleAgent
{
	private static GeneticSchedule $algo;
	private static Cache $cache;
	private static int $cacheTtl = 3600;
	private static int $iterations = 200;

	/**
	 * @return GeneticPerson[]
	 */
	public static function generatePopulation(): array
	{
		return self::$algo->createPopulation(self::$algo->getPopulationSize());
	}

	/**
	 * @param GeneticPerson[] $population
	 *
	 * @return array
	 */
	private static function doIterations(array $population): array
	{
		$newPopulation = self::$algo->doIterations($population, self::$iterations);
		$fit = $newPopulation[0]->getFitness();

		$progress = min(
			round((self::$algo->getLimitOfFitness() / $fit) * 100),
			100
		);

		if (count($newPopulation) === 1)
		{
			$result = [
				'status' => 'finished',
				'schedule' => $newPopulation[0],
				'progress' => $progress,
			];
		}
		else
		{
			$result = [
				'status' => 'inProcess',
				'population' => $newPopulation,
				'progress' => $progress,
			];
		}

		return $result;
	}

	private static function setDataToCache(array $data): void
	{
		self::$cache->cleanDir('/schedule/');
		if (self::$cache->startDataCache(self::$cacheTtl, 'schedule', '/schedule/'))
		{
			self::$cache->endDataCache($data);
		}
	}

	private static function startAlgorithm(int $amountOfPopulations, array $population = []): array
	{
		if (empty($population))
		{
			$population = self::generatePopulation();
		}
		$amountOfPopulations = max($amountOfPopulations, 0);

		if ($amountOfPopulations > self::$algo->getMaxGenerations())
		{
			return [
				'status' => 'finished',
				'schedule' => $population[0],
				'progress' => 100,
			];
		}

		$result = self::doIterations($population);
		$result['amountOfPopulations'] = $amountOfPopulations + self::$iterations;

		return $result;
	}

	public static function testAgent(): string
	{
		self::$algo = new GeneticSchedule();
		self::$cache = Cache::createInstance();
		if (self::$cache->initCache(self::$cacheTtl, 'schedule', '/schedule/'))
		{
			$variables = self::$cache->getVars();
			switch ($variables['status'])
			{
				case 'inProcess':
					$population = $variables['population'];
					$amountOfPopulations = $variables['amountOfPopulations'] ?? 0;
					$result = self::startAlgorithm($amountOfPopulations, $population);
					break;

				case 'notInProcess':
				case 'started':
					$amountOfPopulations = $variables['amountOfPopulations'] ?? 0;
					$result = self::startAlgorithm($amountOfPopulations);
					break;

				case 'finished':
					return '';

				default:
					self::$cache->cleanDir('/schedule/');

					return '';
			}
		}
		else
		{
			$result = self::startAlgorithm(0);
		}

		self::setDataToCache($result);

		$statuses = ['inProcess', 'started', 'notInProcess'];
		if (in_array($result['status'], $statuses, true))
		{
			return "\\Up\\Schedule\\AutomaticScheduleAgent::testAgent();";
		}

		return '';
	}
}
