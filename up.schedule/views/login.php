<?php
/**
 * @var CMain $APPLICATION
 */

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

global $USER;
if ((!is_null($USER)) && $USER->IsAuthorized())
{
	LocalRedirect('/');
}

$APPLICATION->SetTitle("Schedule");

$APPLICATION->IncludeComponent('bitrix:system.auth.authorize', '');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
