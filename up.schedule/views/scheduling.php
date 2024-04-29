<?php
/**
 * @var CMain $APPLICATION
 */

use Bitrix\Main\Context;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Schedule");

$APPLICATION->IncludeComponent('up:sidebar', '', [
]);

$APPLICATION->IncludeComponent('up:automatic.schedule', '', []);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
