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
	// Группы с неполным расписанием
	private EO_Group_Collection $groups;

	// Коллекция всех расставленных пар
	public EO_Couple_Collection $couples;

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
		EO_Subject_Collection $subjects,
	)
	{
		$this->groups = $groups;

		// Заполняем поля класса всевозможными сущностями
		$this->freeAudiencesInCouple = array_fill(1, 6, array_fill(1, 7, $audiences));
		$this->freeTeachersInCouple = array_fill(1, 6, array_fill(1, 7, $teachers));

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
			if ($couple)
			{
				if (!isset($this->couples))
				{
					$couples = new EO_Couple_Collection();
					$couples->add($couple);
					$this->couples = $couples;
				}
				else
				{
					$this->couples->add($couple);
				}

				// TODO: Если у предмета больше 1 часа в неделю
				$this->groups->getByPrimary($couple->getGroup()->getId())
							 ->getSubjects()
							 ->removeByPrimary($couple->getSubject()->getId());

				$this->freeCouplesForGroups[$couple->getGroup()->getId()]
										   [$couple->getWeekDay()]
										   [$couple->getCoupleNumberInDay()]
										   ->removeByPrimary($couple->getSubject()->getId());

				$this->freeTeachersInCouple[$couple->getWeekDay()]
										   [$couple->getCoupleNumberInDay()]
										   ->removeByPrimary($couple->getTeacher()->getId());

				$this->freeAudiencesInCouple[$couple->getWeekDay()]
										   [$couple->getCoupleNumberInDay()]
										   ->removeByPrimary($couple->getAudience()->getId());
			}
		}

		// Вывод
		foreach ($this->couples as $couple)
		{
			echo "--- Пара ---\n";

			echo $couple->group->getTitle() . "\n";
			echo $couple->teacher->getName() . "\n";
			echo $couple->subject->getTitle() . "\n";
			echo $couple->weekDay . "\n";
			echo $couple->coupleNumberInDay . "\n";

			echo "------------\n";
		}
	}

	public function createRandomCouple(): ?EO_Couple
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

		// Ищем свободное у группы место для пары, пока не найдем
		while (true)
		{
			// Берем рандомную пару из свободных
			$isUnique = true;
			$freeCouplesForGroup = $this->freeCouplesForGroups[$randGroup->getId()];
			// TODO: Здесь возможно получение дня, у которого все значения в массиве false!!!
			$randDay = array_rand($freeCouplesForGroup);
			// TODO: Здесь возможно получение дня, у которого все значения в массиве false!!!
			$randCoupleNumber = array_rand($freeCouplesForGroup[$randDay]);

			// Свободные аудитории и преподаватели на этой паре
			$freeAudiences = $this->freeAudiencesInCouple[$randDay][$randCoupleNumber];
			$freeTeachers = $this->freeTeachersInCouple[$randDay][$randCoupleNumber];

			// Берем рандомную аудиторию
			//TODO: Тип аудитории
			$randAudience = $this->getRandEntityFromCollection($freeAudiences);

			// Берем только тех преподавателей, которые преподают данный предмет
			$teachersForSubject = UserRepository::getTeacherBySubjectId($randSubject->getId());

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
				$this->freeCouplesForGroups[$randGroup->getId()][$randDay][$randCoupleNumber]->removeByPrimary(
					$randSubject->getId()
				);
				continue;
			}
			$randTeacher = $this->getRandEntityFromCollection($suitableTeachers);

			// Если ни одной пары нет, то любая группа свободна и можем выходить из поиска
			if (!isset($this->couples))
			{
				break;
			}

			// Проходимся циклом по уже расставленным парам и если это место для группы занято,
			// то убираем его из свободных, выходим из цикла и повторяем рассуждения
			// TODO: Нужен ли этот цикл???
			foreach ($this->couples as $couple)
			{
				if (
					$couple->getWeekDay() === $randDay
					&& $couple->getCoupleNumberInDay() === $randCoupleNumber
					&& $couple->getGroup()->getId() === $randGroup->getId()
				)
				{
					$isUnique = false;
					$this->freeCouplesForGroups[$randGroup->getId()][$randDay][$randCoupleNumber]->removeByPrimary(
						$randSubject->getId()
					);
					break;
				}
			}

			if (!$isUnique)
			{
				foreach ($this->couples as $couple)
				{
					echo $couple->getGroup()->getTitle() . "\n\n";
					echo $couple->getSubject()->getTitle() . "\n\n";
					echo $couple->getTeacher()->getName() . "\n\n";
					echo $couple->getWeekDay() . "\n\n";
					echo $couple->getCoupleNumberInDay() . "\n\n";
					echo $couple->getAudience()->getNumber() . "\n\n";
				}
				echo $randSubject->getTitle() . "\n\n";
				echo $randTeacher->getName() . "\n\n";
				echo $randDay . "\n\n";
				echo $randCoupleNumber . "\n\n";
				var_dump($isUnique);
				die();
			}
			// Если мы нашли уникальную пару, то выходим из цикла поиска
			if ($isUnique)
			{
				break;
			}
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
}