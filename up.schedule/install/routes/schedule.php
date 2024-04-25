<?php

use Bitrix\Main\Context;
use Bitrix\Main\Routing\Controllers\PublicPageController;
use Bitrix\Main\Routing\RoutingConfigurator;
use Up\Schedule\Service\EntityService;
use Up\Schedule\Service\CoupleService;
use Up\Schedule\Service\ImportService;

return static function(RoutingConfigurator $routes) {
	$routes->get('/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));
	$routes->get('/schedule/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));


	$routes->get('/add/couple/select/group/', new PublicPageController('/local/modules/up.schedule/views/select-group.php'));
	$routes->get('/add/couple/group/{groupId}/select/subject/', new PublicPageController('/local/modules/up.schedule/views/select-subject.php'))
		->where('groupId', '[0-9]+');
	$routes->get('/add/couple/group/{groupId}/subject/{subjectId}/', new PublicPageController('/local/modules/up.schedule/views/add-couple.php'))
		->where('groupId', '[0-9]+')
		->where('subjectId', '[0-9]+');
	$routes->post('/add/couple/group/{groupId}/subject/{subjectId}/', function () {
		$subjectId = (int)Context::getCurrent()?->getRequest()->get('subjectId');
		$groupId = (int)Context::getCurrent()?->getRequest()->get('groupId');
		CoupleService::addCouple($groupId, $subjectId);
		LocalRedirect("/add/couple/group/$groupId/subject/$subjectId/");
	});

	$routes->get('/profile/', new PublicPageController('/local/modules/up.schedule/views/profile.php'));
	$routes->get('/admin/', new PublicPageController('/local/modules/up.schedule/views/admin.php'));
	$routes->get('/scheduling/', new PublicPageController('/local/modules/up.schedule/views/scheduling.php'));
	$routes->get('/optimize/', new PublicPageController('/local/modules/up.schedule/views/optimize.php'));
	$routes->get('/statistics/', new PublicPageController('/local/modules/up.schedule/views/statistics.php'));

	$routes
		->where('entity', '[a-zA-Z]+')
		->group(function (RoutingConfigurator $routes) {
			$routes->get('/admin/add/{entity}/', new PublicPageController('/local/modules/up.schedule/views/admins-entity-add.php'));
			$routes->post('/admin/add/{entity}/', function () {
				$entityName = request()->get('entity');
				EntityService::addEntity($entityName);
				LocalRedirect("/admin/#$entityName");
			});
		});

	$routes->get('/import/', new PublicPageController('/local/modules/up.schedule/views/import.php'));
	$routes->post('/import/', new PublicPageController('/local/modules/up.schedule/views/import.php'));

	$routes
		->where('entity', '[a-zA-Z]+')
		->where('id', '[0-9]+')
		->group(function (RoutingConfigurator $routes) {
			$routes->get('/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

			$routes->get('/schedule/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/schedule.php'));

			$routes->get('/admin/edit/{entity}/{id}/', new PublicPageController('/local/modules/up.schedule/views/admins-entity-edit.php'));

			$routes->post('/admin/delete/{entity}/{id}/', function () {
				$entityId = (int)Context::getCurrent()?->getRequest()->get('id');
				$entityName = Context::getCurrent()?->getRequest()->get('entity');
				EntityService::deleteEntityById($entityName, $entityId);
				LocalRedirect("/admin/#$entityName");
			});

			$routes->post('/admin/edit/{entity}/{id}/', function () {
				$entityId = (int)Context::getCurrent()?->getRequest()->get('id');
				$entityName = Context::getCurrent()?->getRequest()->get('entity');
				EntityService::editEntityById($entityName, $entityId);
				LocalRedirect("/admin/edit/$entityName/$entityId/");
			});
		});



	$routes->any('/login/', new PublicPageController('/local/modules/up.schedule/views/login.php'));
	$routes->get('/logout/', function () {
		global $USER;
		$USER->Logout();
		LocalRedirect('/');
	});
};
