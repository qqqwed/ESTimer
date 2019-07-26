<?php
/**
 * Description: 自定义命令
 * Created by Dong.cx
 * DateTime: 2019-07-18 20:32
 * @version V4.0.1
 */

namespace App\Command;


use EasySwoole\EasySwoole\Command\CommandInterface;
use EasySwoole\EasySwoole\Command\Utility;
use EasySwoole\EasySwoole\Logger;

class Test implements CommandInterface
{
	public function commandName(): string
	{
		return 'test';
	}

	public function exec(array $args): ?string
	{
		Logger::getInstance()->log('commond:test的逻辑'.date('Y-m-d h:i:s'));

		return null;
	}

	public function help(array $args): ?string
	{
		//输出logo
		$logo = Utility::easySwooleLog();
		return $logo.<<<HELP_START
		这是test
HELP_START;
	}

}