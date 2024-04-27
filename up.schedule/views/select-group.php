<?php

/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;

const NEED_AUTH = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Schedule|Admin");

$APPLICATION->IncludeComponent('up:sidebar', '');

$APPLICATION->IncludeComponent('up:admins.select.group', '', [
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
