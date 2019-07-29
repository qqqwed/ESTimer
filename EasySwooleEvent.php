<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Crontab\TaskOne;
use App\Crontab\TaskTwo;
use App\Logic\TimerManageLogic;
use App\Process\DbWork;
use App\Process\HotReload;
use App\Process\ProcessTest;
use App\Process\TimerWorker;
use App\Utility\Pool\MysqlPool;
use App\Utility\Template\Smarty;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Crontab\Crontab;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\FastCache\Cache;
use EasySwoole\FastCache\CacheProcessConfig;
use EasySwoole\FastCache\SyncData;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Rpc\NodeManager\RedisManager;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Template\Render;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        /**
         * **************** 加载定时器管理配置文件 ****************
         */
        Config::getInstance()->loadFile(EASYSWOOLE_ROOT.'/timerConf.php', true);

		/**
		 * **************** MYSQL CONFIG ****************
		 */
        $mysqlConf = PoolManager::getInstance()->register(MysqlPool::class, Config::getInstance()->getConf('MYSQL.POOL_MAX_NUM'));
		if ($mysqlConf === null) {
			//当返回null时,代表注册失败,无法进行再次的配置修改
			//注册失败不一定要抛出异常,因为内部实现了自动注册,不需要注册也能使用
			throw new \Exception('注册失败!');
		}
		//设置其他参数
		$mysqlConf->setMaxObjectNum(20)->setMinObjectNum(5);

    }

    public static function mainServerCreate(EventRegister $register)
    {
        /**
         * **************** 热重载进程 ****************
         */
        $swooleServer = ServerManager::getInstance()->getSwooleServer();
        $swooleServer->addProcess((new HotReload('HotReload', ['disableInotify' => false]))->getProcess());
        /**
         * **************** FastCache 快速缓存 ****************
         */
        Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)->attachToServer(ServerManager::getInstance()->getSwooleServer());

        /**
         * **************** 自定义进程 ****************
         */
//        $swooleServer->addProcess((new ProcessTest('Process_test'))->getProcess());
		$swooleServer->addProcess((new DbWork('DbWork'))->getProcess());
		$swooleServer->addProcess((new TimerWorker('TimerWorker'))->getProcess());
		/**
		 * **************** mysql 热启动 ****************
		 */
		$register->add($register::onWorkerStart, function (\swoole_server $server, int $workId) {
			if ($server->taskworker == false) {
				// 为每个worker进程都预创建连接
				PoolManager::getInstance()->getPool(MysqlPool::class)->preLoad(5); //最小创建数
			}
		});

		/**
		 * **************** Crontab任务计划 ****************
		 */
		// 开始一个定时任务计划
//		Crontab::getInstance()->addTask(TaskOne::class);
		// 开始一个定时任务计划
//		Crontab::getInstance()->addTask(TaskTwo::class);


        /**
         * **************** 配置Smarty渲染器 ****************
         */
        Render::getInstance()->getConfig()->setRender(new Smarty());
        Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());

        /**
         * **************** Rpc 服务 ****************
         */
//        $rpcConfig = new \EasySwoole\Rpc\Config();
//        //注册服务名称
//        $rpcConfig->setServerIp('127.0.0.1'); //注册提供rpc服务的ip
//        $rpcConfig->setNodeManager(new RedisManager('127.0.0.1')); //注册节点服务器
//        $rpcConfig->getBroadcastConfig()->setSecretKey('lucky'); //设置秘钥
//
//        $rpc = Rpc::getInstance($rpcConfig);
//        $rpc->add(new TimerManageLogic());
//
//        $rpc->attachToServer(ServerManager::getInstance()->getSwooleServer());
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}