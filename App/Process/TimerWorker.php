<?php
/**
 * Description:
 * Created by Dong.cx
 * DateTime: 2019-07-25 13:37
 * @version V4.0.1
 */

namespace App\Process;


use App\Timer\CronExpression;
use App\Timer\ProcessTask;
use App\Timer\TaskManager;
use App\Timer\TimingWheel;
use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\FastCache\Cache;
use Swoole\Timer;

class TimerWorker extends AbstractProcess
{
    /**
     * 投递任务,如果任务有改变(后台修改了任务),则重新载入任务
     * @param $arg
     *
     * @return mixed
     * @author Dong.cx 2019-07-25 16:02
     * @version V4.0.1
     */
    protected function run($arg)
    {
        go(function(){
            Cache::getInstance()->set('nowTimestamp', time());
            $wheel = new TimingWheel();
            TaskManager::isChange(true);
            \EasySwoole\Component\Timer::getInstance()->loop(1 * 1000, function() use($wheel) {
                $now = Cache::getInstance()->get('nowTimestamp');

                if(TaskManager::isChange()){
                    // 如果任务发生改变,重置时间轮,重新将任务加入时间轮片中
                    $tasks = TaskManager::getTasks();
                    TaskManager::isChange(false);
                    Logger::getInstance()->log('任务发生改变:' . print_r($tasks,true));
                    $wheel->clear();
                    if(!empty($tasks)){
                        foreach ($tasks as $task){
                            $next_run_time = CronExpression::getNextRunTime($task['cron_expression'], $now);
                            $interval = strtotime($next_run_time) - $now;
                            $wheel->add($interval,$task);
                        }
                    }
                }

                // 从时间轮中读取要执行的任务
                $list = $wheel->popSlots();
                Logger::getInstance()->log('从时间轮中读取要执行的任务'.count($list).'个');
                if (!empty($list)) {
                    Logger::getInstance()->log('发现要执行的任务'.count($list).'个');
                    foreach ($list as $task){
                        $next_run_time = CronExpression::getNextRunTime($task['cron_expression'], $now);
                        Logger::getInstance()->log('当前时间：'.date('Y-m-d H:i:s',$now));
                        Logger::getInstance()->log('下次执行：'.$next_run_time);
                        $interval = strtotime($next_run_time) - $now;
                        Logger::getInstance()->log('$interval：'.$interval);
                        $wheel->add($interval,$task);
                        Logger::getInstance()->log('这是要执行的任务'. json_encode($task, JSON_UNESCAPED_UNICODE));
                        Logger::getInstance()->log('准备投递任务');

                        $taskClass = new ProcessTask($task);
                        \EasySwoole\EasySwoole\Swoole\Task\TaskManager::processAsync($taskClass);
                    }
                }
                Cache::getInstance()->set('nowTimestamp', time());
            });
    });


    }

    public function onShutDown()
    {
        // TODO: Implement onShutDown() method.
    }

    public function onReceive(string $str)
    {
        // TODO: Implement onReceive() method.
    }
}