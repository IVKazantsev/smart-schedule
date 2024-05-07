<?php

namespace Up\Schedule\Controller;

use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\Controller;
use Up\Schedule\Service\EntityService;

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
		return EntityService::getCurrentUser()->isAdmin();
	}
}
