<?php

namespace Up\Schedule\Service;

use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\UserRepository;

class CoupleService
{
	public static function addCouple(array $data): void
	{
		CoupleRepository::addCouple($data);
	}

	public static function getCoupleDataBySubjectId(int $subjectId): ?array
	{
		foreach (UserRepository::getTeachersBySubjectId($subjectId) as $teacher)
		{
			$teachers[] = [
				'ID' => $teacher->getId(),
				'TITLE' => $teacher->getName() . " " . $teacher->getLastName(),
			];
		}
		foreach (AudienceRepository::getAudiencesBySubjectId($subjectId) as $audience)
		{
			$audiences[] = [
				'ID' => $audience->getId(),
				'TITLE' => $audience->getNumber(),
			];
		}

		$data['TEACHERS'] = $teachers;
		$data['AUDIENCES'] = $audiences;
		$data['DAYS_OF_WEEK'] = [
			1 => "Понедельник",
			2 => "Вторник",
			3 => "Среда",
			4 => "Четверг",
			5 => "Пятница",
			6 => "Суббота",
		];
		$data['NUMBER_IN_DAY'] = [
			1 => "Первая пара",
			2 => "Вторая пара",
			3 => "Третья пара",
			4 => "Четвертая пара",
			5 => "Пятая пара",
			6 => "Шестая пара",
			7 => "Седьмая пара",
		];

		return $data;
	}
}
