<?php
/**
 * Description: 时间轮
 * Created by Dong.cx
 * DateTime: 2019-07-25 16:05
 * @version V4.0.1
 */

namespace App\Timer;


use EasySwoole\EasySwoole\Logger;

class TimingWheel
{
    public $wheel = [];
    // 该时间轮的槽数
    const LENGTH = 3600;

    public function __construct()
    {
        $this->wheel = array_fill(0,self::LENGTH,[]);
    }
    /**
     * 获取当前时间轮片已走到哪个节点
     * @return float|int
     * @author Dong.cx 2019-07-25 16:16
     * @version V4.0.1
     */
    public function getCurrentIndex()
    {
        $nowTimestamp = TaskManager::$nowTime;
        $minute = date('i', $nowTimestamp);
        $second = date('s', $nowTimestamp);
        return (int)$minute * 60 + (int)$second;
    }

    /**
     * 从时间轮中弹出当前需要执行的任务
     * @return array
     * @author Dong.cx 2019-07-25 16:22
     * @version V4.0.1
     */
    public function popSlots()
    {
        $currentIndex = $this->getCurrentIndex();
        $list = $this->wheel[$currentIndex];
        $slots = [];
        if (!empty($list)) {
            foreach ($list as $key => $item) {
                if ($item['round'] == 0) {
                    $slots[] = $item['data'];
                    unset($this->wheel[$currentIndex][$key]);
                } else {
                    $this->wheel[$currentIndex][$key]['round'] -= 1;
                }
            }
        }
        return $slots;
    }

    /**
     * 新增一个任务到时间轮片中
     * @param int $interval 时间跨度
     * @param $data
     *
     * @author Dong.cx 2019-07-25 16:42
     * @version V4.0.1
     */
    public function add($interval, $data)
    {
        $currentIndex = $this->getCurrentIndex();
        $totalIndex = $currentIndex + $interval;
        // 时间轮的圈数
        $round = intval($interval / self::LENGTH);
        // 槽号
        $index = $totalIndex % self::LENGTH;
        if ($interval % self::LENGTH == 0) {
            $round -= 1;
        }
        $slot = [
            'round' => $round,
            'data'  => $data
        ];
        Logger::getInstance()->log("slot:" . print_r($slot, true));
        $this->wheel[$index][] = $slot;
    }

    /**
     * 重置时间轮
     * @author Dong.cx 2019-07-25 16:50
     * @version V4.0.1
     */
    public function clear()
    {
        $this->wheel = array_fill(0, self::LENGTH, []);
    }
}