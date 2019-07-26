<?php
/**
 * Description: 连接池实现接口
 * Created by Dong.cx
 * DateTime: 2019-07-26 17:21
 * @version V4.0.1
 */

namespace App\Utility\Pool;


use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Mysqli\Config;

class MysqlShopPool extends AbstractPool
{
    /**
     * 创建连接池
     * @return MysqlObject
     * @author Dong.cx 2019-07-26 17:24
     * @version V4.0.1
     */
    protected function createObject()
    {
        $conf = \EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL_SHOP');
        $dbConf = new Config($conf);
        return new MysqlObject($dbConf);
    }

}