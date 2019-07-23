<?php
/**
 * Description: 创建一个连接池对象
 * Created by Dong.cx
 * DateTime: 2019-07-22 18:10
 * @version V4.0.1
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\PoolObjectInterface;
use EasySwoole\Mysqli\Mysqli;

class MysqlObject extends Mysqli implements PoolObjectInterface
{

	/**
	 * 释放对象时会调用
	 * @author Dong.cx 2019-07-22 18:14
	 * @version V4.0.1
	 */
	function gc()
	{
		// 重置为初始状态
		$this->resetDbStatus();
		// 关闭数据库连接
		$this->getMysqlClient()->close();
	}

	/**
	 * 回收对象时会调用
	 * @author Dong.cx 2019-07-22 18:15
	 * @version V4.0.1
	 */
	function objectRestore()
	{
		// 重置为初始状态
		$this->resetDbStatus();
	}

	/**
	 * 每个连接使用之前 都会调用此方法
	 * @return bool 返回 true 表示该连接可用,false 表示该连接已不可用,需要回收
	 * 返回 false 时: @see \EasySwoole\Component\Pool\PoolManager 会回收该连接,并重新进入获取连接流程
	 * @author Dong.cx 2019-07-22 18:17
	 * @version V4.0.1
	 */
	function beforeUse(): bool
	{
		// TODO: 此处可以进行连接是否断线的判断, 使用不同的数据库操作类时可以根据自己情况修改
		return $this->getMysqlClient()->connected;
	}

}