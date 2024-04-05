<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Schedule");

$APPLICATION->IncludeComponent('up:sidebar', '');

$APPLICATION->IncludeComponent('up:couples.list', '', [
	'ENTITY' =>Context::getCurrent()->getRequest()->get('entity'),
	'ID' =>Context::getCurrent()->getRequest()->get('id'),
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
