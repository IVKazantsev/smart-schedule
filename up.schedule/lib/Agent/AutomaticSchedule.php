<?php

namespace Up\Schedule\Agent;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\Binder;
use Up\Schedule\AutomaticSchedule\GeneticPerson;
use Up\Schedule\AutomaticSchedule\GeneticSchedule;

class AutomaticSchedule
{
	private GeneticSchedule $algo;
	//private array $population = [];
	public function startAlgorithm(): array
	{
		$this->algo = new GeneticSchedule();
		return $this->algo->createPopulation(100);
	}

	public function testAgent(string $populationSerialized = '')
	{
		if ($populationSerialized === '')
		{
			$population = $this->startAlgorithm();
		}
		else
		{
			$population = unserialize($populationSerialized, ['allowed_classes' => true]);
		}
		$newPopulation = $this->algo->doIterations($population, 3);
		if (count($newPopulation) === 1)
		{
			$cache = Cache::createInstance();
			if ($cache->initCache(3600, 'schedule'))
			{
				$vars = $cache->getVars();
			}
			elseif ($cache->startDataCache())
			{
				$cache->endDataCache(['schedule' => $population]);
			}
			return $newPopulation;
		}
		$serializedPopulation = serialize($newPopulation);
		return "testAgent($serializedPopulation);";
	}
}
