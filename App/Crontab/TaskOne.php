<?php
/**
 * Description: 定时任务实例一
 * Created by Dong.cx
 * DateTime: 2019-07-22 10:13
 * @version V4.0.1
 */

namespace App\Crontab;


use EasySwoole\EasySwoole\Crontab\AbstractCronTask;

class TaskOne extends AbstractCronTask
{
	/**
	 * 定时周期
	 * @return string
	 * cron表达式规则如下:
	    *    *    *    *    *
		-    -    -    -    -
		|    |    |    |    |
		|    |    |    |    |
		|    |    |    |    +----- day of week (0 - 7) (Sunday=0 or 7)
		|    |    |    +---------- month (1 - 12)
		|    |    +--------------- day of month (1 - 31)
		|    +-------------------- hour (0 - 23)
		+------------------------- min (0 - 59)
	 * 特殊表达式
	    @yearly                    每年一次 等同于(0 0 1 1 *)
		@annually                  每年一次 等同于(0 0 1 1 *)
		@monthly                   每月一次 等同于(0 0 1 * *)
		@weekly                    每周一次 等同于(0 0 * * 0)
		@daily                     每日一次 等同于(0 0 * * *)
		@hourly                    每小时一次 等同于(0 * * * *)
	 * @author Dong.cx 2019-07-22 10:19
	 * @version V4.0.1
	 */
	public static function getRule(): string
	{
		// TODO: Implement getRule() method.
		// 定时周期(每小时)
		return '@hourly';
	}

	public static function getTaskName(): string
	{
		// TODO: Implement getTaskName() method.
		// 定时任务名称
		return 'taskOne';
	}

	static function run(\swoole_server $server, int $taskId, int $fromWorkerId, $flags = null)
	{
		// TODO: Implement run() method.
		// 定时任务逻辑
		var_dump('run once per hour');
	}


}