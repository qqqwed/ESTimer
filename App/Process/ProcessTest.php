<?php
/**
 * Description: 测试自定义进程
 * Created by Dong.cx
 * DateTime: 2019-07-25 20:57
 * @version V4.0.1
 */

namespace App\Process;


use EasySwoole\Component\Process\AbstractProcess;
use EasySwoole\Component\Timer;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\FastCache\Cache;

class ProcessTest extends AbstractProcess
{
    protected function run($arg)
    {

        go(function(){
            Logger::getInstance()->log('Process is Running');
            Cache::getInstance()->set('name', 'this is example');
            $name = Cache::getInstance()->get('name');
            Logger::getInstance()->log('name:' . $name);
        });



//        Timer::getInstance()->loop(1*1000, function(){
//            $age = Cache::getInstance()->get('age');
//            $name = Cache::getInstance()->get('name');
//            Logger::getInstance()->log('读取快速缓存name:'.$name .PHP_EOL . 'age:' . $age);
//        });

    }

}