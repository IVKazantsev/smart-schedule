<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

const NEED_AUTH = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Schedule|Admin");

$APPLICATION->IncludeComponent('up:sidebar', '');

$APPLICATION->IncludeComponent('up:admins.entity.edit', '', [
	'ID' => (int)Context::getCurrent()->getRequest()->get('id'),
	'ENTITY' => Context::getCurrent()->getRequest()->get('entity'),
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
