<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

const NEED_AUTH = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Schedule|Admin");

$APPLICATION->IncludeComponent('up:sidebar', '');

$APPLICATION->IncludeComponent('up:admins.couple.add', '', [
	'GROUP_ID' => (int)Context::getCurrent()->getRequest()->get('groupId'),
	'SUBJECT_ID' => (int)Context::getCurrent()->getRequest()->get('subjectId'),
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
