<?php
/**
 * Description: 连接池实现接口
 * Created by Dong.cx
 * DateTime: 2019-07-22 17:39
 * @version V4.0.1
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Mysqli\Config;

class MysqlPool extends AbstractPool
{
	/**
	 * 创建连接池
	 * 当连接池第一次获取连接时,会调用该方法,返回一个连接池对象
	 * @return MysqlObject
	 * @author Dong.cx 2019-07-22 18:30
	 * @version V4.0.1
	 */
	protected function createObject()
	{
		$conf = \EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL');
		$dbConf = new Config($conf);
		return new MysqlObject($dbConf);
	}

}