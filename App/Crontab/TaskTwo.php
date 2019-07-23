<?php
/**
 * Description:定时任务实例二
 * Created by Dong.cx
 * DateTime: 2019-07-22 10:17
 * @version V4.0.1
 */

namespace App\Crontab;


use EasySwoole\Component\TableManager;
use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class TaskTwo extends AbstractCronTask
{
	public static function getRule(): string
	{
		// TODO: Implement getRule() method.
		// 定时周期 （每两分钟一次）
		return '*/1 * * * *';
	}

	public static function getTaskName(): string
	{
		// TODO: Implement getTaskName() method.
		// 定时任务名称
		return 'taskTwo';
	}

	static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
	{
		// TODO: Implement run() method.
		// 定时任务处理逻辑
		$table = TableManager::getInstance()->get('__CrontabRuleTable');
		var_dump('run once every two minutes'. date('Y-m-d H:i:s'));
		var_dump($table);
	}

}