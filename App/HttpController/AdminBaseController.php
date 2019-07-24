<?php
/**
 * Description:
 * Created by Dong.cx
 * DateTime: 2019-07-23 16:09
 * @version V4.0.1
 */

namespace App\HttpController;


use App\Utility\Template\Smarty;
use EasySwoole\Http\AbstractInterface\Controller;

class AdminBaseController extends Controller
{
    /** @var Smarty 页面渲染器实例*/
    protected static $smarty;

    function index()
    {

    }


    public function __construct()
    {
        parent::__construct();
        self::$smarty = Smarty::getInstance();

        // ip限制
//        $ips = get_setting('admin_limit_ip');


    }

    /**
     * 模板变量赋值
     * @param mixed $name 变量名
     * @param mixed $value 变量值
     *
     * @author Dong.cx 2019-07-24 14:07
     * @version V4.0.1
     */
    public function assign($name, $value = '')
    {
        self::$smarty->assign($name, $value);
    }

    /**
     * 渲染模板数据
     * @param string $template  模板文件名或者内容
     * @param array $data  模板输出变量
     * @param array $options 模板参数
     *
     * @return string|null
     * @throws \SmartyException
     * @author Dong.cx 2019-07-24 14:07
     * @version V4.0.1
     */
    public function fetch(string $template, array $data = [], array $options = [])
    {
        return self::$smarty->fetch($template, $data, $options);

    }

    /**
     * 重启Smarty渲染引擎
     * 重新缓存渲染模板文件
     * @author Dong.cx 2019-07-24 14:37
     * @version V4.0.1
     */
    public function reload_smarty_render()
    {
        self::$smarty->reload();
        $this->response()->write('restart SmartyRender success');
    }
}