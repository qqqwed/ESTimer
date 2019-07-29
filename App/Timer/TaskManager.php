<?php
/**
 * Description: 负责任务管理
 * Created by Dong.cx
 * DateTime: 2019-07-25 14:06
 * @version V4.0.1
 */

namespace App\Timer;


use EasySwoole\EasySwoole\Logger;

class TaskManager
{
    const LOG_KEY = 'cmd_cron_task_log';
    const TASK_KEY = 'cmd_cron_task_list';
    const TASK_IS_CHANGE = 'cmd_cron_task_is_change';
    public static $nowTime;

    /**
     * 如果任务内容改变,则将任务列表重新写到缓存中
     * @param $tasks
     *
     * @return bool 是否有写入到缓存中
     * @author Dong.cx 2019-07-25 14:14
     * @version V4.0.1
     */
    public static function loadTask($tasks): bool
    {
        $oldTask = self::getTasks();
        $oldName = md5(serialize($oldTask));
        $md5Name = md5(serialize($tasks));
        if ($oldName != $md5Name) {
            cache(self::TASK_KEY, $tasks);
            return true;
        }
        return false;
    }

    /**
     * 如果任务改变,则修改标识
     * @param null $tag
     *
     * @return mixed
     * @author Dong.cx 2019-07-25 14:35
     * @version V4.0.1
     */
    public static function isChange($tag = null)
    {
        if (is_null($tag)) {
            return cache(self::TASK_IS_CHANGE);
        }
        cache(self::TASK_IS_CHANGE, $tag);
    }

    /**
     * 读取缓存中的任务列表
     * @return bool|mixed|null
     * @author Dong.cx 2019-07-25 14:12
     * @version V4.0.1
     */
    public static function getTasks()
    {
        return cache(self::TASK_KEY);
    }

    /**
     * 执行任务,并记录日志
     * @param $task
     *
     * @author Dong.cx 2019-07-25 14:35
     * @version V4.0.1
     */
    public static function exec($task)
    {
        $start_time = microtime(true);
        $create_time = date('Y-m-d H:i:s');
//        $task['cmd'] = 'php easyswoole demo';
        $res = \co::exec($task['cmd']);
        Logger::getInstance()->log('执行情况'.json_encode($res));
//        exec($task['cmd']);
        $end_time = microtime(true);
        $spend_time = $end_time - $start_time;
        Logger::getInstance()->log('执行时间'.$spend_time);
        TaskManager::log($task, $create_time, $spend_time);
    }

    /**
     * 将日志写到缓存中
     * @param $cron_task
     * @param $create_time
     * @param $spend_time
     *
     * @author Dong.cx 2019-07-25 14:33
     * @version V4.0.1
     */
    public static function log($cron_task, $create_time, $spend_time)
    {
        $logs = self::getLogs();
        $logs[] = [
            'ct_id'       => $cron_task['id'],
            'cmd'         => $cron_task['cmd'],
            'create_time' => $create_time,
            'spend_time'  => $spend_time
        ];
        cache(self::LOG_KEY, $logs);
    }

    /**
     * 获取所有日志
     * @return mixed
     * @author Dong.cx 2019-07-25 14:32
     * @version V4.0.1
     */
    public static function getLogs(){
        return cache(self::LOG_KEY);
    }

    /**
     * 清除缓存中所有日志
     * @author Dong.cx 2019-07-25 16:53
     * @version V4.0.1
     */
    public static function clearLogs()
    {
        cache(self::LOG_KEY, []);
    }

    /**
     * 将任务标记为无
     * @author Dong.cx 2019-07-25 16:53
     * @version V4.0.1
     */
    public static function clear()
    {
        self::loadTask([]);
    }

    /**
     * 保存此时的时间
     * @author Dong.cx 2019-07-25 16:13
     * @version V4.0.1
     */
    public static function tick()
    {
        static::$nowTime = time();
    }


}