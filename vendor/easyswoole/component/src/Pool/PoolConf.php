<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/12/4
 * Time: 12:05 PM
 */

namespace EasySwoole\Component\Pool;


use EasySwoole\Component\Pool\Exception\PoolObjectNumError;

class PoolConf
{
    protected $intervalCheckTime = 30*1000;	// 定时验证对象是否可用以及保持最小连接的间隔时间
    protected $maxIdleTime = 15;	// 最大存活时间,每隔30秒($intervalCheckTime默认值)会将15秒($maxIdleTime默认值)未使用的连接彻底释放
    protected $maxObjectNum = 20;	// 最大创建数量
    protected $minObjectNum = 5;	// 最小创建数量,最小创建数量不能大于最大创建数量,否则会报错,每$intervalCheckTime/1000秒去判断最小连接数
    protected $getObjectTimeout = 3.0;	// 连接池对象回去连接对象时的等待时间

    protected $extraConf;

    /**
     * @return float|int
     */
    public function getIntervalCheckTime()
    {
        return $this->intervalCheckTime;
    }

    /**
     * @param $intervalCheckTime
     * @return PoolConf
     */
    public function setIntervalCheckTime($intervalCheckTime): PoolConf
    {
        $this->intervalCheckTime = $intervalCheckTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxIdleTime(): int
    {
        return $this->maxIdleTime;
    }

    /**
     * @param int $maxIdleTime
     * @return PoolConf
     */
    public function setMaxIdleTime(int $maxIdleTime): PoolConf
    {
        $this->maxIdleTime = $maxIdleTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxObjectNum(): int
    {
        return $this->maxObjectNum;
    }

    public function setMaxObjectNum(int $maxObjectNum): PoolConf
    {
        if($this->minObjectNum >= $maxObjectNum){
            throw new PoolObjectNumError('min num is bigger than max');
        }
        $this->maxObjectNum = $maxObjectNum;
        return $this;
    }

    /**
     * @return float
     */
    public function getGetObjectTimeout(): float
    {
        return $this->getObjectTimeout;
    }

    /**
     * @param float $getObjectTimeout
     * @return PoolConf
     */
    public function setGetObjectTimeout(float $getObjectTimeout): PoolConf
    {
        $this->getObjectTimeout = $getObjectTimeout;
        return $this;
    }

    public function getExtraConf()
    {
        return $this->extraConf;
    }

    /**
     * @param $extraConf
     * @return PoolConf
     */
    public function setExtraConf($extraConf): PoolConf
    {
        $this->extraConf = $extraConf;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinObjectNum(): int
    {
        return $this->minObjectNum;
    }

    public function setMinObjectNum(int $minObjectNum): PoolConf
    {
        if($minObjectNum >= $this->maxObjectNum){
            throw new PoolObjectNumError('min num is bigger than max');
        }
        $this->minObjectNum = $minObjectNum;
        return $this;
    }

}