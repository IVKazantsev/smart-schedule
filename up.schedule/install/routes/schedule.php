<?php

use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;

return static function(RoutingConfigurator $routes) {
	$routes->get('/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/schedule/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/schedule/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

	$routes->get('/profile/', new PublicPageController('/local/modules/up.schedule/views/profile.php'));
	$routes->get('/admin/', new PublicPageController('/local/modules/up.schedule/views/admin.php'));
	$routes->get('/scheduling/', new PublicPageController('/local/modules/up.schedule/views/scheduling.php'));
	$routes->get('/optimize/', new PublicPageController('/local/modules/up.schedule/views/optimize.php'));
	$routes->get('/statistics/', new PublicPageController('/local/modules/up.schedule/views/statistics.php'));
	$routes->get('/add-couple/', new PublicPageController('/local/modules/up.schedule/views/add-couple.php'));

	$routes->get('/admin/edit/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/edit-form.php'));


	$routes->any('/login/', new PublicPageController('/local/modules/up.schedule/views/login.php'));
	$routes->get('/logout/', function () {
		global $USER;
		$USER->Logout();
		LocalRedirect('/');
	});
};
