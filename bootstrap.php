<?php
/**
 * Description:框架初始化之前执行自定义事件
 * Created by Dong.cx
 * DateTime: 2019-07-18 20:40
 * @version V4.0.1
 */

\EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set(new \App\Command\Test());
\EasySwoole\EasySwoole\Command\CommandContainer::getInstance()->set(new \App\Command\Test2());