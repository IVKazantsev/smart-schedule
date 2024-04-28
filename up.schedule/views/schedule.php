<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Schedule");

$APPLICATION->IncludeComponent('up:sidebar', '', [
	'ENTITY' => Context::getCurrent()->getRequest()->get('entity') ?? 'group',
]);

$APPLICATION->IncludeComponent('up:schedule.display.entities.list', '', [
	'ENTITY' =>Context::getCurrent()->getRequest()->get('entity'),
	'ID' =>Context::getCurrent()->getRequest()->get('id'),
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
