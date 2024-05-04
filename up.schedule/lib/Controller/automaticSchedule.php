<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Type\DateTime;
use Up\Schedule\AutomaticSchedule\GeneticPerson;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Service\EntityService;
use Up\Schedule\Service\ImportService;

class AutomaticSchedule extends Controller
{
	public function getCurrentStatusAction(): array
	{
		//$cache = Cache::createInstance();
		$status = 'notInProcess';

		$cache = Cache::createInstance();

		if ($cache->initCache(3600, 'schedule', '/schedule/'))
		{
			$variablesOfAgentCache = $cache->getVars();
			$statusAgent = $variablesOfAgentCache['status'];
			$progressAgent = $variablesOfAgentCache['progress'];
		}

		//$cacheController = Cache::createInstance();
		if ($cache->initCache(3600, 'scheduleStatus', '/scheduleStatus/'))
		{
			$variablesOfStatusCache = $cache->getVars();
			$statusOfControllerCache = $variablesOfStatusCache['status'];
			$progressOfControllerCache = $variablesOfStatusCache['progress'];
		}

		$status = $statusAgent ?? ($statusOfControllerCache ?? 'notInProcess');

		$progress = max($progressAgent??0, $progressOfControllerCache??0);

		$this->saveInfoInControllerCache($status, $progress);


		//$cache->abortDataCache();
/*
		if ($cache->initCache(3600, 'scheduleStatus', '/scheduleStatus/'))
		{
			$variablesOfStatusCache = $cache->getVars();
			$statusOfControllerCache = $variablesOfStatusCache['status'];
			$progressOfControllerCache = $variablesOfStatusCache['progress'];
		}

		if ($cache->startDataCache(3600, 'scheduleStatus', '/scheduleStatus/'))
		{
			$status = $statusAgent ?: $statusOfControllerCache;
			$progress = max($progressAgent, $progressOfControllerCache);

			$cache->endDataCache(['status' => $status, 'progress' => $progress]);
		}*/
//		if ($status === 'finished')
//		{
//			$schedule = $variables['schedule'];
//			/*self::clearCache('schedule');
//			$cache = Cache::createInstance();
//			if ($cache->startDataCache(3600, 'finalSchedule', '/finalSchedule/'))
//			{
//				$cache->endDataCache(['status' => 'finished', 'finalSchedule' => $schedule]);
//			}*/
//		}
		//if ($agents = \CAgent::GetList([], ['MODULE_ID' => 'up.schedule'])?->Fetch())
//		{
//			$cache = Cache::createInstance();
//			if ($cache->initCache(3600, 'schedule', '/schedule/'))
//			{
//				$vars = $cache->getVars();
//				//$status = 'inProcessTest';
//			}
//			$status = 'inProcess';
//		}
//		else
//		{
//			$status = 'notInProcess';
//		}
		return [
			'status' => $status,
			'progress' => $progress,
			];
	}

	private function saveInfoInControllerCache(string $status, int $progress): void
	{
		$cache = Cache::createInstance();
//		BXClearCache(true, '/scheduleStatus/');
		if ($cache->startDataCache(3600, 'scheduleStatus', '/scheduleStatus/'))
		{
			$cache->endDataCache(['status' => $status, 'progress' => $progress]);
		}
	}

	public function setGeneratedScheduleAction(): array
	{
		CoupleRepository::deleteAllFromDB();
		$couples = $this->getArrayForAddToDb();
		EntityService::addCouplesToDB($couples);
		self::clearCache('schedule');
		self::clearCache('scheduleStatus');
		return ['result' => true];
	}

	public function cancelGeneratedScheduleAction(): array
	{
		self::clearCache('schedule');
		self::clearCache('scheduleStatus');
		return ['result' => true];
	}

	public function getCouplesListAction(string $entity, int $id): array
	{
		if ($this->isValidEntityName($entity) && $id > 0)
		{
			$couples = $this->fetchCouples($entity, $id);
		}
		else
		{
			$couples = [];
		}
		return [
			'couples' => $couples,
		];
	}

	private function fetchCouples(string $entity, int $id): array
	{
		/*$getMethodName = "getArrayFor$entity";
		$couples = $this->$getMethodName($id);*/
		$couples = $this->getArrayOfCouples($entity, $id);
		return $this->sortCouplesByWeekDay($couples);
	}

	private function getArrayForAddToDb(): array
	{
		$couples = $this->getCouplesFromCache();
		$couplesArray = [];
		foreach ($couples as $couple)
		{
			$subject = $couple->getSubject();
			$audience = $couple->getAudience();
			$teacher = $couple->getTeacher();
			$group = $couple->getGroup();

			$couplesArray[] = [
				$group->getTitle(),
				$subject->getTitle(),
				$audience->getNumber(),
				$teacher->getName(),
				$teacher->getLastName(),
				$couple->getWeekDay(),
				$couple->getCoupleNumberInDay(),
			];
		}
		return $couplesArray;
	}

	private function getArrayOfCouples(string $entity, int $id): array
	{
		$couples = $this->getCouplesFromCache();
		$couplesArray = [];
		$getMethodName = 'get' . ucfirst($entity) . 'Id';
		foreach ($couples as $couple)
		{
			if ($couple->$getMethodName() !== $id)
			{
				continue;
			}

			$subject = $couple->getSubject();
			$audience = $couple->getAudience();
			$teacher = $couple->getTeacher();
			$group = $couple->getGroup();

			$couplesArray[] = [
				'COUPLE_NUMBER_IN_DAY' => $couple->getCoupleNumberInDay(),
				'WEEK_DAY' => $couple->getWeekDay(),
				'UP_SCHEDULE_MODEL_COUPLE_SUBJECT_ID' => $subject->getId(),
				'UP_SCHEDULE_MODEL_COUPLE_SUBJECT_TITLE' => $subject->getTitle(),
				'UP_SCHEDULE_MODEL_COUPLE_SUBJECT_AUDIENCE_TYPE_ID' => $subject->getAudienceTypeId(),
				'UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_ID' => $audience->getId(),
				'UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_NUMBER' => $audience->getNumber(),
				'UP_SCHEDULE_MODEL_COUPLE_AUDIENCE_AUDIENCE_TYPE_ID' => $audience->getAudienceTypeId(),
				'UP_SCHEDULE_MODEL_COUPLE_TEACHER_ID' => $teacher->getId(),
				'UP_SCHEDULE_MODEL_COUPLE_TEACHER_LOGIN' => $teacher->getLogin(),
				'UP_SCHEDULE_MODEL_COUPLE_TEACHER_NAME' => $teacher->getName(),
				'UP_SCHEDULE_MODEL_COUPLE_TEACHER_LAST_NAME' => $teacher->getLastName(),
				'UP_SCHEDULE_MODEL_COUPLE_GROUP_ID' => $group->getId(),
				'UP_SCHEDULE_MODEL_COUPLE_GROUP_TITLE' => $group->getTitle(),
			];
		}
		return $couplesArray;
	}

	public function getCouplesFromCache(): array
	{
		$cache = Cache::createInstance();
		if ($cache->initCache(3600, 'schedule', '/schedule/'))
		{
			$variables = $cache->getVars();
			return $variables['schedule']?->couples->getAll();
		}

		return [];
	}

	private function sortCouplesByWeekDay(?array $couples): array
	{
		$sortedCouples = [];
		foreach ($couples as $couple)
		{
			$weekDay = $couple['WEEK_DAY'];
			$numberOfCouple = $couple['COUPLE_NUMBER_IN_DAY'];
			$sortedCouples[$weekDay][$numberOfCouple] = $couple;
		}

		return $sortedCouples;
	}

	private static function clearCache(string $directory): void
	{
		if ($directory !== '')
		{
			$directory = "/$directory/";
		}
		$cache = Cache::createInstance();
		$cache->cleanDir($directory);
	}

	public function generateScheduleAction(): array
	{
		//$result = '\\Up\\Schedule\\Agent\\AutomaticSchedule::testAgent();';

		/*$cache = Cache::createInstance();
		$cache->cleanDir( '/schedule/');*/
		self::clearCache('scheduleStatus');
		$cache = Cache::createInstance();

		$cache->initCache(3600, 'scheduleStatus', '/scheduleStatus/');
		//$cache->forceRewriting(true);
		if ($cache->startDataCache(3600, 'scheduleStatus', '/scheduleStatus/'))
		{
			$cache->endDataCache(['status' => 'started', 'progress' => 0]);
		}

		$result = \CAgent::AddAgent(
			"\\Up\\Schedule\\AutomaticScheduleAgent::testAgent();",
			"up.schedule",
			"N",
			1,
			"",
			"Y",
		);

		if($result)
		{
			return ['result' => true];
		}

		return ['result' => false];
	}

	public function cancelGenerateScheduleAction(): array
	{
		\CAgent::RemoveAgent("\\Up\\Schedule\\AutomaticScheduleAgent::testAgent();", "up.schedule");

		self::clearCache('schedule');
		self::clearCache('scheduleStatus');


		return ['result' => true];
	}

	private function isValidEntityName($entityName): bool
	{
		$availableEntityNames = [
			'group',
			'teacher',
			'audience',
		];

		return in_array($entityName, $availableEntityNames, true);
	}
}
