<?php

namespace Up\Schedule\AutomaticSchedule;

use Bitrix\Main\EO_User;
use Bitrix\Main\EO_User_Collection;
use Up\Schedule\Model\EO_Audience;
use Up\Schedule\Model\EO_Audience_Collection;
use Up\Schedule\Model\EO_Couple;
use Up\Schedule\Model\EO_Couple_Collection;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Group_Collection;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Repository\UserRepository;

class GeneticPerson
{
	private int $fitness;

	// Группы с неполным расписанием
	private EO_Group_Collection $groups;

	// Коллекция всех расставленных пар
	public EO_Couple_Collection $couples;

	public function setCouples(EO_Couple_Collection $couples): void
	{
		$this->couples = $couples;
	}

	// Массив, в котором на месте [i][j] лежит коллекция свободных для пары в i день на j месте аудиторий
	public array $freeAudiencesInCouple;

	// Массив, в котором на месте [i][j] лежит колллекция свободных для пары в i день на j месте преподавателей
	public array $freeTeachersInCouple;

	// Массив, в котором на месте [i][j][k] лежит коллекция предметов,
	// которые можно поставить для i группы в j день на k месте
	public array $freeCouplesForGroups;

	public function __construct(
		EO_Group_Collection $groups,
		EO_Audience_Collection $audiences,
		EO_User_Collection $teachers,
	)
	{
		$this->groups = clone $groups;
		$this->couples = new EO_Couple_Collection();
		// Заполняем поля класса всевозможными сущностями
		for ($i = 1; $i <= 6; $i++)
		{
			for ($j = 1; $j <= 7; $j++)
			{
				$this->freeAudiencesInCouple[$i][$j] = new EO_Audience_Collection();
				$this->freeAudiencesInCouple[$i][$j]->merge($audiences);
				$this->freeTeachersInCouple[$i][$j] = new EO_User_Collection();
				$this->freeTeachersInCouple[$i][$j]->merge($teachers);
			}
		}

		foreach ($this->groups as $group)
		{
			$this->freeCouplesForGroups[$group->getId()] = array_fill(1, 6, array_fill(1, 7, $group->getSubjects()));
		}

		// Генерируем пары, пока не закончатся группы, у которых есть нераставленные пары
		while (!$this->groups->isEmpty())
		{
			$couple = $this->createRandomCouple();

			// Если получилось создать пару, то:
			// 1. Добавляем созданную пару в коллекцию пар
			// 2. В выбранной группе удаляем предмет, пару по которому добавили
			// 3. Убираем "свободность" выбранных сущностей
			if ($couple === null)
			{
				continue;
			}
			if ($couple === false)
			{
				echo "СОСТАВИТЬ РАСПИСАНИЕ НЕ УДАЛОСЬ";
				break;
			}

			$this->couples->add($couple);

			// TODO: Если у предмета больше 1 часа в неделю
			$this->groups->getByPrimary($couple->getGroup()->getId())->getSubjects()->removeByPrimary(
					$couple->getSubject()->getId()
				);

			$this->freeCouplesForGroups[$couple->getGroup()->getId()]
			[$couple->getWeekDay()]
			[$couple->getCoupleNumberInDay()]?->removeByPrimary($couple->getSubject()->getId());

			$this->freeTeachersInCouple[$couple->getWeekDay()]
			[$couple->getCoupleNumberInDay()]?->removeByPrimary($couple->getTeacher()->getId());

			$this->freeAudiencesInCouple[$couple->getWeekDay()]
			[$couple->getCoupleNumberInDay()]?->removeByPrimary($couple->getAudience()->getId());
		}
	}

	public function createRandomCouple(): null|EO_Couple|false
	{
		// Берем рандомную группу из неполностью занятых групп
		$randGroup = $this->getRandEntityFromCollection($this->groups);

		// Получаем коллекцию всех нераставленных предметов и проверяем, что такие еще остались
		// И если таких больше нет, то удаляем группу из коллекции, меняем ее "свободность" и прекрашаем
		$subjectsInGroup = $this->groups->getByPrimary($randGroup->getId())->getSubjects();
		if ($subjectsInGroup->isEmpty())
		{
			$this->groups->removeByPrimary($randGroup->getId());
			unset($this->freeCouplesForGroups[$randGroup->getId()]);

			return null;
		}

		// Получаем коллекцию всех нераставленных предметов и берем рандомный
		$randSubject = $this->getRandEntityFromCollection($subjectsInGroup);

		$freeCouplesForSubject = [];

		// Ищем свободные места для рандомно полученного предмета
		foreach ($this->freeCouplesForGroups[$randGroup->getId()] as $dayKey => $day)
		{
			foreach ($day as $coupleKey => $subjectCollection)
			{
				if ($subjectCollection->hasByPrimary($randSubject->getId()))
				{
					$freeCouplesForSubject[$dayKey][$coupleKey] = true;
				}
			}
		}
		// Если мест нет, то имеем, что у группы есть невыставленный предмет => расписание нам не подходит
		if (empty($freeCouplesForSubject))
		{
			return false;
		}
		// Ищем свободное у группы место для пары, пока не найдем
		while (true)
		{
			// Берем рандомный день из свободных
			$randDay = array_rand($freeCouplesForSubject);

			// Если рандомно полученный день пуст, т.е. в нем нет свободных пар, то удаляем его из массива
			if (empty($freeCouplesForSubject[$randDay]))
			{
				unset($freeCouplesForSubject[$randDay]);
				continue;
			}

			// Берем рандомную пару из свободных
			$randCoupleNumber = array_rand($freeCouplesForSubject[$randDay]);

			// Избавляемся от ситуации, когда имеем пустую коллекцию предметов, которые можно выставить в этой паре
			if ($this->freeCouplesForGroups[$randGroup->getId()][$randDay][$randCoupleNumber]?->isEmpty())
			{
				unset($this->freeCouplesForGroups[$randGroup->getId()][$randDay][$randCoupleNumber]);
				continue;
			}

			// Свободные аудитории и преподаватели на этой паре
			$freeAudiences = $this->freeAudiencesInCouple[$randDay][$randCoupleNumber];
			// Избавляемся от ситуации, когда имеем пустую коллекцию свободных аудиторий в этой паре
			if ($freeAudiences->isEmpty())
			{
				unset($this->freeAudiencesInCouple[$randDay][$randCoupleNumber]);
				continue;
			}

			$freeTeachers = $this->freeTeachersInCouple[$randDay][$randCoupleNumber];

			// Избавляемся от ситуации, когда имеем пустую коллекцию свободных преподавателей в этой паре
			if ($freeTeachers === null)
			{
				unset($freeCouplesForSubject[$randDay][$randCoupleNumber]);
			}
			if ($freeTeachers->count() === 0)
			{
				unset($this->freeTeachersInCouple[$randDay][$randCoupleNumber]);
				continue;
			}

			// Берем рандомную аудиторию
			//TODO: Тип аудитории
			$randAudience = $this->getRandEntityFromCollection($freeAudiences);

			// Берем только тех преподавателей, которые преподают данный предмет
			$teachersForSubject = UserRepository::getTeacherBySubjectId($randSubject->getId());

			if ($teachersForSubject->count() === 0)
			{
				return false;
			}

			// Находим подходящих преподавателей и берем рандомного
			$suitableTeachers = new EO_User_Collection();
			foreach ($teachersForSubject as $teacher)
			{
				if ($freeTeachers->hasByPrimary($teacher->getId()))
				{
					$suitableTeachers->add($teacher);
				}
			}

			// Если подходящих преподавателей нет, то удаляем предмет из возможной расстановки
			if ($suitableTeachers->count() === 0)
			{
				$this->freeCouplesForGroups[$randGroup->getId()][$randDay][$randCoupleNumber]?->removeByPrimary(
					$randSubject->getId()
				);
				unset($freeCouplesForSubject[$randDay][$randCoupleNumber]);
				continue;
			}
			$randTeacher = $this->getRandEntityFromCollection($suitableTeachers);
			break;
		}

		$couple = new EO_Couple();
		$couple->setCoupleNumberInDay($randCoupleNumber);
		$couple->setAudience($randAudience);
		$couple->setGroup($randGroup);
		$couple->setSubject($randSubject);
		$couple->setTeacher($randTeacher);
		$couple->setWeekDay($randDay);

		return $couple;
	}

	public function getRandEntityFromCollection(
		EO_Group_Collection|EO_Subject_Collection|EO_Audience_Collection|EO_User_Collection $collection
	): EO_Group|EO_Subject|EO_Audience|EO_User
	{
		$idList = $collection->getIdList();
		$randKeyOfId = array_rand($idList);
		$randId = $idList[$randKeyOfId];

		return $collection->getByPrimary($randId);
	}

	public function getFitness(): int
	{
		return $this->fitness;
	}

	public function setFitness(int $fitness): void
	{
		$this->fitness = $fitness;
	}
}
