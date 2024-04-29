<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\CurrentUser;
use Up\Schedule\Repository\AudienceRepository;
use Up\Schedule\Repository\AudienceTypeRepository;
use Up\Schedule\Repository\GroupRepository;
use Up\Schedule\Repository\SubjectRepository;
use Up\Schedule\Repository\UserRepository;

class UserRole extends Controller
{
	protected function getDefaultPreFilters(): array
	{
		return [
			new HttpMethod(
				[HttpMethod::METHOD_GET, HttpMethod::METHOD_POST]
			),
			new Csrf(),
		];
	}

	public function isAdminAction(): bool
	{
		$user = CurrentUser::get();
		if(!$user)
		{
			return false;
		}

		return $user->isAdmin();
	}
}
