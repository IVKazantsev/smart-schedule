<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

const NEED_AUTH = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Schedule|Admin");

// global $USER;
// if ((!is_null($USER)) && $USER->IsAuthorized())
// {
// 	LocalRedirect('/');
// }

// $APPLICATION->IncludeComponent('bitrix:system.auth.authorize', '');

$APPLICATION->IncludeComponent('up:sidebar', '');

$APPLICATION->IncludeComponent('up:edit.form', '', [
	'ENTITY' => Context::getCurrent()->getRequest()->get('entity'),
	'ID' => Context::getCurrent()->getRequest()->get('id'),
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
