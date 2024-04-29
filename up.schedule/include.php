<?php

use Up\Schedule\Agent\AutomaticSchedule;
use Bitrix\Main\Application;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\Request;

const COUPLES_NUMBER_PER_DAY = 7;

\Bitrix\Main\Loader::autoLoad(AutomaticSchedule::class);

function request(): Request
{
	return Application::getInstance()?->getContext()->getRequest();
}

function db(): Connection
{
	return Application::getConnection();
}

if (file_exists(__DIR__ . '/module_updater.php'))
{
	include (__DIR__ . '/module_updater.php');
}
