<?php
/**
 * @var CMain $APPLICATION
 */

const NEED_AUTH = true;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Schedule|Admin");

$APPLICATION->IncludeComponent('up:sidebar', '');

$APPLICATION->IncludeComponent('up:admin.panel', '');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
