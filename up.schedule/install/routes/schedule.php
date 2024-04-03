<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return static function(RoutingConfigurator $routes) {
	$routes->get('/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/schedule/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
};