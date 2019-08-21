# ESTimer（开发中）
基于easyswoole + layui 的毫秒级异步定时任务管理
##TODO:
当前版本下 脚本的监控执行已完成,视图界面待开发
## EasySwoole运行步骤

EasySwoole的框架运行步骤大概为以下几步:

- 从php easyswoole start开始,首先进行了目录常量定义,临时目录,日志目录定义,
- 触发`initialize`,这个事件你可以进行一些服务注册,修改临时目录,日志目录等
- 获取框架配置,监听ip,端口,worker进程数,子服务配置,回调等,准备开启swoole服务
- 触发`mainServerCreate`,这个事件你可以自行重新配置监听ip,端口,回调事件,框架异常,等等
- 框架根据配置,启动swoole服务,附带子服务(如果有配置的话)

到这个时候,框架已经是启动成功了,由于swoole_server的特性,开启之后会常驻内存(进程会一直运行,可以理解成一直在while(1){}),等待请求进入然后回调.
用户请求步骤:

- 用户请求
- swoole_server触发回调事件,经过框架解析
- 触发 `onRequest`(http服务时),`onReceive`(tcp服务时)
- 经过http组件的调度,调用控制器方法完毕
- 触发 `afterRequest` 事件,表明这次请求已经要结束
- es将响应数据交回给swoole_server,给客户端响应数据

# 全局事件

EasySwoole有五个全局事件，全部位于框架安装后生成的EasySwooleEvent.php中。

- initialize 框架初始化事件
- mainServerCreate 主服务创建事件
- onRequest Http请求事件
- afterRequest Http响应后事件

# 配置文件

### 加载额外配置文件

在 `EasySwooleEvent.php` ,使用 `Config` 类的 `loadFile` 方法进行加载

```php
public static function initialize()
{
    date_default_timezone_set('Asia/Shanghai');  
    Config::getInstance()->loadFile(EASYSWOOLE_ROOT.'/timerConf.php', true);
}

```

# 文件热加载

由于 `swoole` 常驻内存的特性，修改文件后需要重启worker进程才能将被修改的文件重新载入内存中，我们可以自定义Process的方式实现文件变动自动进行服务重载

# 模板引擎

## 安装smarty模板引擎

> ```php
> composer require smarty/smarty=~3.1
> ```

## 服务注册

```php
//在全局的主服务中创建事件中，实例化该Render,并注入你的驱动配置
Render::getInstance()->getConfig()->setRender(new Smarty());
Render::getInstance()->getConfig()->setTempDir(EASYSWOOLE_TEMP_DIR);
Render::getInstance()->attachServer(ServerManager::getInstance()->getSwooleServer());
```

## 渲染页面方式

### 方式1：

封装了Smarty渲染器，并在AdminBaseController中封装了助手函数

```php
$this->assign('user', 'martini');
$this->response()->write($this->fetch('index/index.html'));
```

### 方式2：

底层实现

```php
$this->response()->write(Render::getInstance()->render('index/index.html',[
    'user'=>'easyswoole',
    'time'=>time()
]));
```



#缓存

### FastCache

EasySwoole 提供了一个快速缓存，是基础UnixSock通讯和自定义进程存储数据实现的，提供基本的缓存服务，本缓存为解决小型应用中，需要动不动就部署Redis服务而出现。

#### 安装

```
composer require easyswoole/fast-cache
```

#### 服务注册

我们在EasySwoole全局的事件中进行注册

```
use EasySwoole\FastCache\Cache;
Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)->attachToServer(ServerManager::getInstance()->getSwooleServer());
```

> > FastCache只能在服务启动之后使用,需要有创建unix sock权限(建议使用vm,docker或者linux系统开发),虚拟机共享目录文件夹是无法创建unix sock监听的

#### 客户端调用

服务启动后，可以在任意位置调用

```
use EasySwoole\FastCache\Cache;
Cache::getInstance()->set('get','a');
var_dump(Cache::getInstance()->get('get'));
```



# 数据库连接池

## 获取连接对象的使用方式

### 方式一：defer

```php
// 协程单例模式+连接池 [占用时间长，一直到当前协程退出]
// 在当前协程下，是单例的，可以很方便的使用事务
$db = MysqlPool::defer();

//如：
$db = MysqlPool::defer();
$db->where('id',1)->get('tb_user');
```

注意：使用defer，一般都是相同的db对象，使用协程则会不同

```php
$db1 = MysqlPool::defer();
$db2 = MysqlPool::defer();

//此时 $db1 === $db2, 使用的是同一个连接

// 在协程中再连接一个，此时$db3和$db1,$db2不是一个连接
go(function () {
    $db3 = MysqlPool::defer();
}); 

```

### 方式二：invoke

```php
// 立即从连接池中取出一个连接对象 [随用随还，占用时间少]
// 执行完后马上回收掉该对象，返回结果
MysqlPool::invoke();

// 如：
MysqlPool::invoke(function(MysqlObject $mysqlObject) {
    return $mysqlObject->where('id', 1)->get('tb_user');
}) 

```

### 方式三：从池中获取一个空闲连接对象

该方式是上述两种方式的底层实现

```php
$db = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj();
```

# 请求响应

### 请求响应类方法

- request
  request方法调用之后,将返回`EasySwoole\Http\Request`对象
  该对象附带了用户请求的所有数据,例如:

  ```php
  function index()
  {
  $request = $this->request();
  $request->getRequestParam();//获取post/get数据,get覆盖post
  $request->getMethod();//获取请求方式(post/get/)
  $request->getCookieParams();//获取cookie参数
  }
  ```

  > 更多request相关可查看[request对象](https://www.easyswoole.com/Cn/Http/request.html)

- response
  response方法将返回`EasySwoole\Http\Response`,用于向客户端响应数据,例如:

  ```php
  function index()
  {
  $response = $this->response();
  $response->withStatus(200);//设置响应状态码,必须设置
  $response->setCookie('name','仙士可',time()+86400,'/');//设置一个cookie
  $response->write('hello world');//向客户端发送一条数据(类似于常规web模式的 echo )
  }
  ```

  > 更多response相关可查看[response对象](https://www.easyswoole.com/Cn/Http/response.html)

- writeJson
  writeJson方法直接封装了设置响应状态码,设置响应头,数组转为json输出.

  ```php
  function index()
  {
  $this->writeJson(200,['xsk'=>'仙士可'],'success');
  }
  ```

  网页输出:

  ```
  {"code":200,"result":{"xsk":"仙士可"},"msg":"success"}
  ```

# Model

 **注意：**

```PHP
$db = MysqlPool::defer();

$userModel = new UserModel($db);
$authModel = new AuthModel($db);

$user->db()->where('user_id', 1);
$sql = $authModel->db()->getOne('user_auth_list');

print_r($sql);

此时会输出  
    select * from user_auth_list where user_id = 1;
会导致串行问题
    
如何解决？
    每个UserModel，AuthModel 都提供一个查询构造器，避免上层发生上述操作，也就是说UserModel 只提供一个完整的查询给上层
```

## 事务

```php

```



# PRC

## 服务端

```php
<?php
use EasySwoole\Rpc\Config;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Rpc\NodeManager\RedisManager;
use EasySwoole\Rpc\Test\UserService;
use EasySwoole\Rpc\Test\OrderService;
use EasySwoole\Rpc\Test\NodeService;
use EasySwoole\EasySwoole\ServerManager;

#在EasySwooleEvent.php  的全局 mainServerCreate 事件中注册

$config = new Config();
$config->setServerIp('127.0.0.1');//注册提供rpc服务的ip
$config->setNodeManager(new RedisManager('127.0.0.1'));//注册节点管理器
$config->getBroadcastConfig()->setSecretKey('lucky');        //设置秘钥        

$rpc = Rpc::getInstance($config);;
$rpc->add(new UserService());  //注册服务
$rpc->add(new OrderService());
$rpc->add(new NodeService());
$rpc->add(new TimerManageLogic());

$rpc->attachToServer(ServerManager::getInstance()->getSwooleServer());
```



```php
<?php

namespace App\Logic;


use EasySwoole\Http\Message\Status;
use EasySwoole\Rpc\AbstractService;

class TimerManageLogic extends AbstractService
{
    /**
     *
     * @return string
     * @author Dong.cx 2019-07-25 10:47
     * @version V4.0.1
     */
    public function serviceName(): string
    {
        // TODO: Implement serviceName() method.
        return 'timer';
    }

    public function run()
    {
        $arg = $this->request()->getArg();
        var_dump($arg);
        $this->response()->setResult(['uid'=>22]);
        $this->response()->setStatus(Status::CODE_OK);
        $this->response()->setMsg('this is running');
    }

}
```





## 客户端

```php
	public function public_test_rpc()
    {
        $data = [
            'command' => 1,//1:请求,2:状态rpc 各个服务的状态
            'request' => [
                'serviceName' => 'timer',
                'action' => 'run',//行为名称
                'arg' => [
                    'args1' => 'args1',
                    'args2' => 'args2'
                ]
            ]
        ];

        //$raw = serialize($data);//注意序列化类型,需要和RPC服务端约定好协议 $serializeType

        $raw = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $fp = stream_socket_client('tcp://127.0.0.1:9600');
        fwrite($fp, pack('N', strlen($raw)) . $raw);//pack数据校验

        $data = fread($fp, 65533);
        //做长度头部校验
        $len = unpack('N', $data);
        $data = substr($data, '4');
        if (strlen($data) != $len[1]) {
            echo 'data error';
        } else {
            $data = json_decode($data, true);
            //    //这就是服务端返回的结果，
            var_dump($data);//默认将返回一个response对象 通过$serializeType修改
        }
        fclose($fp);
    }
```

# 问题

### co::exec 执行后，下面的代码都不执行了，可能是什么原因导致的

>```
>在调度中加，
>Timer::clearAll();
>定时器清除，由于没有➕定时器清除导致，swoole的定时器没有清，协程一直卡在那里
>```

### 如何处理耗时任务

起一个常驻服务，并起几个固定的进程，如果有特别耗时的任务，就将任务分发给进程