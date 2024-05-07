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
				$daysOfWeek[$i] = GetMessage('DAY_' . $i . '_OF_WEEK');
			}
			else
			{
				break;
			}
		}

		return $daysOfWeek;
	}

	public static function getCoupleNumbers(): array
	{
		$coupleNumbers = [];
		for ($i = 1; $i <= COUPLES_NUMBER_PER_DAY; $i++)
		{
			if(GetMessage('DAY_' . $i . '_OF_WEEK'))
			{
				$coupleNumbers[$i] = GetMessage($i . '_COUPLE');
			}
			else
			{
				break;
			}
		}

		return $coupleNumbers;
	}
}