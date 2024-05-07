<?php

use Bitrix\Main\Context;
use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Schedule\Service\EntityService;

return static function(RoutingConfigurator $routes) {
	$routes->get('/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/schedule/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

	$routes->get('/admin/', new PublicPageController('/local/modules/up.schedule/views/admin.php'));
	$routes->post('/admin/', new PublicPageController('/local/modules/up.schedule/views/admin.php'));

	$routes->get('/scheduling/', new PublicPageController('/local/modules/up.schedule/views/scheduling.php'));
	$routes->get('/scheduling/preview/', new PublicPageController('/local/modules/up.schedule/views/scheduling-preview.php'));
	$routes->get('/scheduling/preview/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/scheduling-preview.php'))
		->where('entity', '[a-zA-Z]+')
		->where('id', '[0-9]+');

	$routes
		->where('entity', '[a-zA-Z]+')
		->group(function (RoutingConfigurator $routes) {
			$routes->get('/admin/add/{entity}/', new PublicPageController('/local/modules/up.schedule/views/admins-entity-add.php'));
			$routes->post('/admin/add/{entity}/', new PublicPageController('/local/modules/up.schedule/views/admins-entity-add.php'));
		});

	$routes->get('/import/', new PublicPageController('/local/modules/up.schedule/views/import.php'));
	$routes->post('/import/', new PublicPageController('/local/modules/up.schedule/views/import.php'));

	$routes
		->where('entity', '[a-zA-Z]+')
		->where('id', '[0-9]+')
		->group(function (RoutingConfigurator $routes) {
			$routes->get('/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

			$routes->get('/schedule/{sidebarEntity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

			$routes->get('/admin/edit/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/admins-entity-edit.php'));
			$routes->post('/admin/edit/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/admins-entity-edit.php'));

			$routes->post('/admin/delete/{entity}/{id}/', function () {
				$entityId = (int)Context::getCurrent()?->getRequest()->get('id');
				$entityName = Context::getCurrent()?->getRequest()->get('entity');
				if(!check_bitrix_sessid())
				{
					LocalRedirect("/admin/#$entityName");
				}
				EntityService::deleteEntityById($entityName, $entityId);
				LocalRedirect("/admin/#$entityName");
			});
		});

	$routes->any('/login/', new PublicPageController('/local/modules/up.schedule/views/login.php'));
	$routes->get('/logout/', function () {
		global $USER;
		$USER->Logout();
		LocalRedirect('/');
	});
};
