<?php
/**
 * Description: 路由
 * Created by Dong.cx
 * DateTime: 2019-07-22 16:23
 * @version V4.0.1
 */

namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
	function initialize(RouteCollector $routeCollector)
	{
		$routeCollector->get('/test', 'Index/test_mysql');
		$routeCollector->get('/user/get_one', '/Admin/User/getOne');
		$routeCollector->post('/admin/user/create', '/Admin/User/create');
	}

}