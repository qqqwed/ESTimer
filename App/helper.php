<?php
/**
 * Description: 助手工具
 * Created by Dong.cx
 * DateTime: 2019-07-23 17:03
 * @version V4.0.1
 */
use EasySwoole\FastCache\Cache;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\MysqlObject;

if (!function_exists('cache')) {
    /**
     * 缓存管理
     * FastCache组件通过新开进程,使用SplArray存储,unix sock 高速通信方式,实现了多进程共享数据.
     * @param string $key 缓存key
     * @param string $value  需要缓存的内容(可序列化的内容都可缓存)
     * @param int|null $ttl 缓存有效时间
     * @param float $timeout socket等待超时
     *
     * @return mixed
     * @author Dong.cx 2019-07-23 18:24
     * @version V4.0.1
     */
    function cache($key, $value = '', ?int $ttl = null, float $timeout = 1.0)
    {
        $cache = Cache::getInstance();
        if ('' === $value) {
            // 获取缓存
            return $cache->get($key, $timeout);

        } elseif (is_null($value)) {
            // 删除缓存
            return $cache->unset($key, $timeout);
        } else {
            // 缓存数据
            return $cache->set($key, $value, $ttl, $timeout);
        }
    }
}

if (!function_exists('get_setting')) {
    /**
     * 获取配置项
     * @param string $name 配置项名称
     * @param string $tableName 配置表名称
     *
     * @return bool|mixed|null
     * @throws Throwable
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @author Dong.cx 2019-07-23 20:39
     * @version V4.0.1
     */
    function get_setting($name, $tableName = SETTING)
    {
        $key = 'estimer_setting_' . $name;
        $value = cache($key);

        if ($value) {
            return $value;
        }

        $value = MysqlPool::invoke(function(MysqlObject $mysqlObject) use ($name, $tableName){
            $mysqlObject->where('name', $name)->getOne($tableName, 'value');
        });
        cache($key, $value);
        return $value;
    }
}