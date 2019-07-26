<?php
/**
 * Description:基础逻辑层
 * Created by Dong.cx
 * DateTime: 2019-07-23 14:39
 * @version V4.0.1
 */

namespace App\Logic;


use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\MysqlShopPool;

abstract class BaseLogic
{
    /** @var MysqlObject */
    public $db;

    public function __construct()
    {
        $this->db = MysqlShopPool::defer();
    }

    /**
     * @return MysqlObject
     */
    public function getDb(): MysqlObject
    {
        return $this->db;
    }

    /**
     * @param MysqlObject $db
     */
    public function setDb(MysqlObject $db): void
    {
        $this->db = $db;
    }

    /**
     * 开启事务
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @author Dong.cx 2019-07-23 14:47
     * @version V4.0.1
     */
    public function startTrans()
    {
        $this->db->startTransaction();
    }

    /**
     * 提交事务
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @author Dong.cx 2019-07-23 14:48
     * @version V4.0.1
     */
    public function commit()
    {
        $this->db->commit();
    }

    /**
     * 事务回滚
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @author Dong.cx 2019-07-23 14:48
     * @version V4.0.1
     */
    public function rollback()
    {
        $this->db->rollback();
    }
}