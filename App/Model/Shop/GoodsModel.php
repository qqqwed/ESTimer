<?php
/**
 * Description: 商品model层
 * Created by Dong.cx
 * DateTime: 2019-07-26 17:41
 * @version V4.0.1
 */

namespace App\Model\Shop;


use App\Model\BaseModel;

class GoodsModel extends BaseModel
{
    protected $tableName = 'tb_goods';
    protected $pk = 'gid';

//TODO: 原则---下层暴露给上层的API,期望的更新方式,查询方式

    /**
     * 新增商品
     * @param GoodsBean $bean
     *
     * @return GoodsBean|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     * @author Dong.cx 2019-07-26 17:57
     * @version V4.0.1
     */
    public function create(GoodsBean $bean): ?GoodsBean
    {
        $id = $this->db()->insert($this->tableName, $bean->toArray(null, $bean::FILTER_NOT_NULL));
        if ($id) {
            $bean->setGid($id);
            return $bean;
        }
        return null;
    }

    /**
     * 更新数据
     * @param GoodsBean $bean
     * @param array $data
     *
     * @return int|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     * @author Dong.cx 2019-07-26 18:30
     * @version V4.0.1
     */
    public function update(GoodsBean $bean, array $data)
    {
        $this->db()->where('gid', $bean->getGid())->update($this->tableName, $data);
        return $this->db()->getAffectRows();
    }

    /**
     * 查询一条记录
     * @param GoodsBean $bean
     *
     * @return GoodsBean|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     * @author Dong.cx 2019-07-26 18:30
     * @version V4.0.1
     */
    public function getOne(GoodsBean $bean)
    {
        $goods = $this->db()->where('gid', $bean->getGid())->getOne($this->tableName);
        if (empty($goods)) return null;
        return new GoodsBean($goods);
    }

    /**
     * !!!注意这里是物理删除 删除数据
     * @param GoodsBean $bean
     *
     * @return bool|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     * @author Dong.cx 2019-07-26 18:30
     * @version V4.0.1
     */
    public function delete(GoodsBean $bean)
    {
        return $this->db()->where('gid', $bean->getGid())->delete($this->tableName);
    }
}