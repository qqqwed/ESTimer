<?php
/**
 * Description:投递任务模板类
 * Created by Dong.cx
 * DateTime: 2019-07-25 20:37
 * @version V4.0.1
 */

namespace App\Timer;


use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

class ProcessTask extends AbstractAsyncTask
{
    /**
     * 执行任务的逻辑
     * @param mixed $taskData 任务数据
     * @param int $taskId 执行任务的task编号
     * @param int $fromWorkerId 派发任务的worker进程号
     * @param null $flags
     *
     * @return bool
     * @author Dong.cx 2019-07-25 20:12
     * @version V4.0.1
     */
    protected function run($taskData, $taskId, $fromWorkerId, $flags = null)
    {
        Logger::getInstance()->log('开始执行任务'.print_r($taskData,true));
        TaskManager::exec($taskData);
        return true;
    }

    /**
     * 任务执行完的回调
     * @param mixed $result 任务执行完成返回的结果
     * @param int $task_id 执行任务的task编号
     *
     * @return mixed
     * @author Dong.cx 2019-07-25 20:12
     * @version V4.0.1
     */
    protected function finish($result, $task_id)
    {
        // TODO: Implement finish() method.
    }

}