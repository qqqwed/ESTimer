<?php
/**
 * Description:
 * Created by Dong.cx
 * DateTime: 2019-07-18 19:45
 * @version V4.0.1
 */

namespace App\HttpController;


use App\Utility\Pool\MysqlObject;
use App\Utility\Pool\MysqlPool;
use App\Utility\Template\Smarty;
use EasySwoole\Component\TableManager;
use EasySwoole\FastCache\Cache;
use EasySwoole\Http\AbstractInterface\Controller;
use EasySwoole\Http\Message\Status;
use EasySwoole\Template\Render;

class IndexController extends AdminBaseController
{
	function index()
	{
        $this->response()->write($this->fetch('index/index.html'));
//	    //		$this->response()->write('hello easySwoole');
//        Render::getInstance()->restartWorker();
//        $this->response()->write(Render::getInstance()->render('index/index.html'));
//
//        $this->response()->write(Smarty::getInstance()->render('index/index.html',[
//            'user'=>'easyswoole',
//            'time'=>time()
//        ]));
//        $this->assign('user', 'martini');
//        $this->response()->write($this->fetch('index/index.html'));

	}

	public function test_mysql()
	{
		$table_name = 'jt_user';

		/**
		 * @var MysqlObject $db
		 */
		$db = MysqlPool::defer();
		$data = $db->getOne($table_name);
		$this->writeJson(Status::CODE_OK, $data);
	}

	public function test_fast_cache()
    {
//        Cache::getInstance()->set('name', ['id'=>3]);
//        $data = Cache::getInstance()->get('name');
         cache('name', 'martini');
         $data = cache('name');

//        var_dump($data);
        $this->writeJson(Status::CODE_OK, $data);
    }

}