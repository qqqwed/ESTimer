<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2019-01-01
 * Time: 20:06
 */

return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
		'LISTEN_ADDRESS' => '0.0.0.0',
		'PORT'           => 9501,
		'SERVER_TYPE'    => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
		'SOCK_TYPE'      => SWOOLE_TCP,
		'RUN_MODEL'      => SWOOLE_PROCESS,
		'SETTING'        => [
			'worker_num'            => 8,
			'task_worker_num'       => 8,
			'reload_async'          => true,
			'task_enable_coroutine' => true,
			'max_wait_time'         => 3
		],
	],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,

	/**
	 * **************** MYSQL CONFIG ****************
	 */
	'MYSQL'         => [
		'host'          => '116.62.127.139',
		'port'          => '3306',
		'user'          => 'wldev',
		'password'      => '7BmkOM2RaooiQw5S',
		'database'      => 'wljob_dev',
		'timeout'       => '5',
		'charset'       => 'utf8mb4',
		'POOL_MAX_NUM'  => '10',
		'POOL_TIME_OUT' => '0.1'
	],
	/**
	 * **************** MYSQL CONFIG 多数据库情况同上(略) ****************
	 */

];