<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Type\DateTime;

class AutomaticSchedule extends Controller
{
	public function getCurrentStatusAction(): array
	{
		$status = '';
		if ($agents = \CAgent::GetList([], ['MODULE_ID' => 'up.schedule'])?->Fetch())
		{
			$cache = Cache::createInstance();
			if ($cache->initCache(3600, 'schedule'))
			{
				$vars = $cache->getVars();
			}
			$status = 'inProcess';
		}
		else
		{
			$status = 'notInProcess';
		}
		return [
			'status' => $status,
			'progress' => $vars,
			];
	}

	public function generateScheduleAction(): array
	{
		//$result = '\\Up\\Schedule\\Agent\\AutomaticSchedule::testAgent();';

		$result = \CAgent::AddAgent(
			'AutomaticSchedule::testAgent();',
			'up.schedule',
			'Y',
			'20',
			'',
			'Y',
			''
		);
		return ['result' => $result];
	}

	public function cancelGenerateScheduleAction(): array
	{
		\CAgent::RemoveAgent('AutomaticSchedule::testAgent();', 'up.schedule');
		return ['result' => true];
	}
}
