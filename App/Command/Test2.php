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

class Test2 implements CommandInterface
{
	public function commandName(): string
	{
		return 'test2';
	}

	public function exec(array $args): ?string
	{
		//打印参数,打印测试值
		var_dump($args);
		echo 'test'.PHP_EOL;
		Logger::getInstance()->log('commond:test2的逻辑'.date('Y-m-d h:i:s'));
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