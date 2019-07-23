<?php
/**
 * Description:
 * Created by Dong.cx
 * DateTime: 2019-07-22 17:00
 * @version V4.0.1
 */

namespace App\HttpController\Admin;


use App\Model\UserBean;
use App\Model\UserModel;
use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\MysqliPool\Mysql;

class User extends Controller
{
	function index()
	{
		// TODO: Implement index() method.
	}

	public function create()
	{
		// TODO: 传入参数
		$params = [];

		/** @var MysqlObject $db */
		$db = MysqlPool::defer();
		$userModel = new UserModel($db);

		go(function () {
            $db = MysqlPool::defer();
        });

		$userBean = new UserBean($params);

		$userModel->create($userBean);
		if (!empty($userBean)) {
            $this->writeJson(Status::CODE_OK, $userBean->toArray(['user_id']));
            return;
        }

		$this->writeJson(Status::CODE_INTERNAL_SERVER_ERROR, null, '服务器开小差了!');
	}

	public function getOne()
	{
		$db = MysqlPool::defer();
		$userModel = new UserModel($db);
		$data = $userModel->getOne();
		$this->writeJson(Status::CODE_OK, $data);
	}


}