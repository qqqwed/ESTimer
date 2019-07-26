<?php
/**
 * Description:Bean类(数据对象),用于定义表结构,过滤无效字段
 * GoodsModel是对数据表的抽象,GoodsBean是对一行数据的抽象
 * Created by Dong.cx
 * DateTime: 2019-07-26 17:43
 * @version V4.0.1
 */

namespace App\Model\Shop;


use EasySwoole\Spl\SplBean;

class GoodsBean extends SplBean
{
    protected $gid;
    protected $goods_code;
    protected $is_gy;
    protected $transport_id;
    protected $goods_name;
    protected $supplier_id;
    protected $spec_info;
    protected $sales_price;
    protected $market_price;
    protected $supplier_price;
    protected $stock;
    protected $sales_volume;
    protected $list_img;
    protected $imgs_more;
    protected $goods_label;
    protected $need_mail;
    protected $isshow;
    protected $pay_type;
    protected $list_order;
    protected $abstract;
    protected $content;
    protected $create_user;
    protected $create_time;
    protected $update_user;
    protected $update_time;
    protected $sts = 0;

    /**
     * @return mixed
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param mixed $gid
     */
    public function setGid($gid): void
    {
        $this->gid = $gid;
    }


    /**
     * @return mixed
     */
    public function getUpdateUser()
    {
        return $this->update_user;
    }

    /**
     * @param mixed $update_user
     */
    public function setUpdateUser($update_user): void
    {
        $this->update_user = $update_user;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * @param mixed $update_time
     */
    public function setUpdateTime($update_time): void
    {
        $this->update_time = $update_time;
    }


}