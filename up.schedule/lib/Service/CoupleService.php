<?php

namespace Up\Schedule\Service;

use Bitrix\Main\Context;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\CoupleRepository;
use Up\Schedule\Repository\UserRepository;

class CoupleService
{
	public static function addCouple(int $groupId, int $subjectId): void
	{
		/*var_dump(Context::getCurrent()?->getRequest()->getPostList()->toArray());*/
		$parameters = Context::getCurrent()?->getRequest()->getPostList()->toArray();
		$teacherId = (int)$parameters['TEACHERS'];
		$audienceId = (int)$parameters['AUDIENCES'];
		$dayOfWeek = (int)$parameters['DAYS_OF_WEEK'];
		$numberInDay = (int)$parameters['NUMBER_IN_DAY'];

		$data = [
			'GROUP_ID' => $groupId,
			'SUBJECT_ID' => $subjectId,
			'TEACHER_ID' => $teacherId,
			'AUDIENCE_ID' => $audienceId,
			'DAY_OF_WEEK' => $dayOfWeek,
			'NUMBER_IN_DAY' => $numberInDay,
		];

		/*var_dump($data); die;*/
		CoupleRepository::addCouple($data);
	}

	public static function getCoupleDataBySubjectId(int $subjectId): ?array
	{
		foreach (UserRepository::getTeachersBySubjectId($subjectId) as $teacher)
		{
			$teachers[] = [
				'ID' => $teacher->getId(),
				'TITLE' => $teacher->getName() . " " . $teacher->getLastName()
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
