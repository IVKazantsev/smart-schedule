<?php

namespace Up\Schedule\Service;

class LocalizationService
{
	public static function getWeekDays(): array
	{
		$daysOfWeek = [];
		for ($i = 1; $i <= 7; $i++)
		{
			if(GetMessage('DAY_' . $i . '_OF_WEEK'))
			{
				$daysOfWeek[] = GetMessage('DAY_' . $i . '_OF_WEEK');
			}
			else
			{
				break;
			}
		}

		return $daysOfWeek;
	}
}