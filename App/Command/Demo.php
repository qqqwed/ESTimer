<?php
/**
 * Description: 自定义命令
 * Created by Dong.cx
 * DateTime: 2019-07-18 20:32
 * @version V4.0.1
 */

namespace App\Command;


use App\Logic\DemoLogic;
use EasySwoole\EasySwoole\Command\CommandInterface;
use EasySwoole\EasySwoole\Command\Utility;
use EasySwoole\EasySwoole\Logger;

class Demo implements CommandInterface
{
    //TODO:该命令 需要在根目录 bootstrap.php 进行注册
	public function commandName(): string
	{
		return 'demo';
	}

	public function exec(array $args): ?string
	{
		//打印参数,打印测试值
		var_dump($args);
		echo 'test'.PHP_EOL;
		Logger::getInstance()->log('commond:demo的逻辑'.date('Y-m-d h:i:s'));
		$demoLogic = new DemoLogic();
		$demoLogic->updateGoodsTime();
		return null;
	}

	public function help(array $args): ?string
	{
		//输出logo
		$logo = Utility::easySwooleLog();
		return $logo.<<<HELP_START
		这是demo
HELP_START;
	}

}