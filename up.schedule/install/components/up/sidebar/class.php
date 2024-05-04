<?php

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\AutomaticSchedule\GeneticPerson;
use Up\Schedule\AutomaticSchedule\GeneticSchedule;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class SidebarComponent extends CBitrixComponent
{
	private array $entitiesForDisplaySchedule = [
		'group',
		'audience',
		'teacher',
	];

	public function executeComponent(): void
	{
//		echo "<pre>";
//		for($i = 0; $i < 1; $i++)
//		{
//			$algo = new GeneticSchedule();
//			$algo->createPopulation($algo->getPopulationSize());
////			$string = \Up\Schedule\AutomaticScheduleAgent::testAgent();
////			if ($string === '')
////			{
////				break;
////			}
//		}
//		$algo = new GeneticSchedule();
//		echo "<pre>";
//		$population = $algo->createPopulation($algo->getPopulationSize());
//		//$algo->geneticAlgorithm(100);
//
//		$population = $algo->doIterations($population, 10);

//		$cache = Cache::createInstance();
//		if ($cache->initCache(3600, 'schedule', '/schedule/'))
//		{
//			$variables = $cache->getVars();
//			/*return '';*/
//			if ($variables['status'] === 'inProcess') {
//				$population = unserialize($variables['population'], ['allowed_classes' => true]);
//				//$result = self::doIterations($population);
//				$newPopulation = $algo->doIterations($population, 10);
//				$fit = $newPopulation[0]->getFitness();
//				if (count($newPopulation) === 1) {
//					$result = [
//						'status' => 'finished',
//						'schedule' => serialize($newPopulation),
//						'progress' => $fit,
//					];
//					// завершить агента прогрессбара
//				} else {
//					$result = [
//						'status' => 'inProcess',
//						'population' => serialize($newPopulation),
//						'progress' => $fit,
//					];
//				}
//				/*return '';*/
//			}
//		}
//		$population = $alg->createPopulation(10);
//		$population[0]->setFitness(10);
//		//echo implode(' ', $population[0]->couples->getGroupIdList());
//		$serialized = serialize($population);
//		$unserialized = unserialize($serialized, ['allowed_classes' => true]);
//		$newPopulation = $alg->doIterations($unserialized, 3);
//
//		$serialized = serialize($newPopulation);
//		$unserialized = unserialize($serialized, ['allowed_classes' => true]);
//		$newPopulation = $alg->doIterations($unserialized, 3);
		/*echo "<br>";
		echo implode(' ', $unserialized[0]->couples->getGroupIdList());*/
		$this->prepareTemplateParams();
		$this->fetchUserInfo();
		$this->includeComponentTemplate();
	}

	protected function fetchUserInfo(): void
	{
		$user = CurrentUser::get();
		$isAdmin = $user->isAdmin();
		$userId = $user->getId();
		$user = UserRepository::getById($userId);

		if ($user)
		{
			if ($isAdmin)
			{
				$this->arResult['USER_ROLE'] = 'Администратор';
			}
			else
			{
				$this->arResult['USER_ROLE'] = $user->get('UP_SCHEDULE_ROLE')?->get('TITLE') ?? 'Гость';
			}
			$this->arResult['USER_NAME'] = $user->getName();
			$this->arResult['USER_LAST_NAME'] = $user->getLastName();

			$this->arResult['IS_AUTHORIZED'] = true;

			return;
		}

		$this->arResult['USER_ROLE'] = 'Гость';

		$this->arResult['IS_AUTHORIZED'] = false;
	}

	protected function prepareTemplateParams(): void
	{
		$this->arResult['ENTITY'] = $this->arParams['ENTITY'];
		$this->arResult['ENTITIES_FOR_DISPLAY'] = $this->entitiesForDisplaySchedule;
		$this->arResult['LOC_ENTITIES_FOR_DISPLAY'] = array_map(static function(string $elem) {
			return strtoupper($elem);
		}, $this->entitiesForDisplaySchedule);
	}
}
