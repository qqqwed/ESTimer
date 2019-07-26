<?php
/**
 * Description:用户表model层
 * Created by Dong.cx
 * DateTime: 2019-07-22 17:01
 * @version V4.0.1
 */

namespace App\Model;


class UserModel extends BaseModel
{
	protected $tableName = 'jt_user';
	protected $pk = 'id';

    // TODO: 下层暴露给上层的API,期望的更新,查询方式
    /**
     * 创建数据
     * @param UserBean $bean
     *
     * @return UserBean|null
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     * @author Dong.cx 2019-07-23 09:53
     * @version V4.0.1
     */
	public function create(UserBean $bean): ?UserBean
	{

		$id = $this->db()->insert($this->tableName, $bean->toArray(null, $bean::FILTER_NOT_NULL));
		if ($id) {
            $bean->setId($id);
            return $bean;
        }
        return null;
	}
    // TODO: 只暴露出期望的更新方式
	public function update(UserBean $bean): bool
	{
	    // 这里只允许更新username,password
		return $this->db()->where($this->pk, $bean->getId())->update($this->tableName, $bean->toArray(['username','password']));
	}

	public function updateByUserName()
    {

    }

	/**
	 *
	 * @return \EasySwoole\Mysqli\Mysqli|mixed|null
	 * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
	 * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
	 * @throws \Throwable
	 * @author Dong.cx 2019-07-22 20:57
	 * @version V4.0.1
	 */
	public function getOne()
	{
		return $this->db()->getOne($this->tableName);
	}

	public function getAll()
	{
		
	}
}