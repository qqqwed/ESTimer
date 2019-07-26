<?php
/**
 * Description: 负责数据库IO操作
 * 定时从数据库中读取任务列表,写入缓存中
 * Created by Dong.cx
 * DateTime: 2019-07-25 13:37
 * @version V4.0.1
 */

namespace App\Process;


use App\Timer\TaskManager;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\FastCache\Cache;
use Swoole\Timer;

class DbWork extends AbstractProcess
{
    /**
     * 启动定时器读取任务列表,写入缓存
     * @param $arg
     *
     * @return mixed
     * @author Dong.cx 2019-07-25 13:59
     * @version V4.0.1
     */
    protected function run($arg)
    {
        go(function(){
            TaskManager::clearLogs();
            \EasySwoole\Component\Timer::getInstance()->loop(5 * 1000, function(){
                // 将数据库中的任务写入到缓存中
                /** @var $db MysqlObject */
                $db = MysqlPool::defer();
                $list = $db->where('status', 1)->get(CRON_TASK);
                if (TaskManager::loadTask($list)) {
                    TaskManager::isChange(true);
                }
                // 将缓存中的日志写到数据库中
                $logList = TaskManager::getLogs();
//                Logger::getInstance()->log('缓存中的日志' . json_encode($logList));
                if (!empty($logList)) {
                    try {
                        $db->insertMulti(CRON_TASK_LOG, $logList);
                    } catch (\Throwable $e) {
                        Logger::getInstance()->log('log err >' . $e->getMessage());
                    }
                }

                // 清除之前的旧日志
                $day = get_setting('cron_task_log_save_day');
                $key = 'has_delete_log_'.date('Y-m-d') . '_'.$day;
                if($day > 0 && cache($key) != true){ //清除日志一天只需执行一次即可
                    $db->where('create_time','<',date('Y-m-d 00:00:00',strtotime("-$day day")))->delete(CRON_TASK_LOG);
                    Cache::getInstance()->set($key, true, 24*60*60);
//                    cache($key,true,24*60*60);
                }
            });
        });

    }

}