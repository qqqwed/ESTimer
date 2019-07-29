<?php
/**
 * Description:测试任务逻辑
 * Created by Dong.cx
 * DateTime: 2019-07-26 18:40
 * @version V4.0.1
 */

namespace App\Logic;


use App\Model\Shop\GoodsBean;
use App\Model\Shop\GoodsModel;
use App\Utility\Pool\MysqlShopPool;
use EasySwoole\EasySwoole\Logger;

class DemoLogic
{
    /**
     * 更新指定商品的修改时间
     * @author Dong.cx 2019-07-26 18:57
     * @version V4.0.1
     */
    public function updateGoodsTime()
    {
        go(function () {
            $db = MysqlShopPool::defer();
            $goodsModel = new GoodsModel($db);
            $time = time();
            $data = ['gid'=>70,'update_time'=>time()];
            $goodsBean = new GoodsBean($data);
            $goodsModel->update($goodsBean, $data);
            Logger::getInstance()->log("成功执行更新商品时间任务,修改时间为{$time}即". date('Y-m-d H:i:s', $time));
            return true;
        });
    }
}