<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return static function(RoutingConfigurator $routes) {
	$routes->get('/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/schedule/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

	$routes->get('/profile/', new PublicPageController('/local/modules/up.schedule/views/profile.php'));
	$routes->get('/make-schedule/', new PublicPageController('/local/modules/up.schedule/views/make-schedule.php'));
	$routes->get('/optimize/', new PublicPageController('/local/modules/up.schedule/views/optimize.php'));
	$routes->get('/statistics/', new PublicPageController('/local/modules/up.schedule/views/statistics.php'));
	$routes->get('/add-couple/', new PublicPageController('/local/modules/up.schedule/views/add-couple.php'));
};