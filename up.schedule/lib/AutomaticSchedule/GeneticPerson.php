<?php

namespace Up\Schedule\AutomaticSchedule;

use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Objectify\Collection;
use Up\Schedule\Model\Couple;
use Up\Schedule\Model\EO_Group;
use Up\Schedule\Model\EO_Group_Collection;
use Up\Schedule\Model\EO_Subject;
use Up\Schedule\Model\EO_Subject_Collection;
use Up\Schedule\Model\GroupTable;

class GeneticPerson
{

	/**
	 * @var EO_Group[]
	 */
	private array $groups; // Группы.

	/**
	 * @var Couple[]
	 */
	public array $couples;

	/**
	 * @var int[]
	 */
	public array $hoursForSubjects;

	/**
	 * @var int[]
	 */
	public array $necessaryHoursForSubjects;

	public int $audience;

	public int $teacher;

	public int $weekDay;

	public int $numberInDay;

	public function __construct()
	{
		$this->groups = GroupTable::query()->setSelect(['ID', 'TITLE', 'SUBJECTS'])->fetchAll();
		while (true)
		{
			if(empty($this->groups))
			{
				break;
			}
			$couple = $this->createRandomCouple();
			if($couple)
			{
				$this->couples[] = $couple;
				var_dump($couple->subject->getTitle());
				$group = $this->groups->getByPrimary($couple->group->getId());
				$group->getSubjects()->remove($couple->subject);

				$this->groups->removeByPrimary($group->getId());
				$this->groups->add($group);
			}
		}

		var_dump($this->couples);
	}

	public function createRandomCouple(): ?Couple
	{
		$randGroup = $this->getRandEntityFromCollection($this->groups);
		if(!$randGroup)
		{
			return null;
		}

		$subjectsInGroup = $this->groups->getByPrimary($randGroup->getId())->getSubjects();
		$randSubject = $this->getRandEntityFromCollection($subjectsInGroup);
		if(!$randSubject)
		{
			$this->groups->remove($randGroup);
			return null;
		}

		return new Couple($randGroup, $randSubject);
	}

	public function getRandEntityFromCollection(EO_Group_Collection|EO_Subject_Collection $collection): EO_Group|EO_Subject|null
	{
		if($collection->isEmpty())
		{
			return null;
		}
		$idList = $collection->getIdList();
		$randKeyOfId = array_rand($idList);
		$randId = $idList[$randKeyOfId];

		return $collection->getByPrimary($randId);
	}
}