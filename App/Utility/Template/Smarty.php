<?php
/**
 * Description:Smarty渲染器
 * 实现RenderInterface接口,
 * 当进程收到一条渲染指令时，会调用该实现类的render方法进行渲染，渲染结束后调用afterRender方法，可在此处进行变量释放清理等操作
 * Created by Dong.cx
 * DateTime: 2019-07-24 09:48
 * @version V4.0.1
 */

namespace App\Utility\Template;


use EasySwoole\Component\Singleton;
use EasySwoole\Template\Render;
use EasySwoole\Template\RenderInterface;

class Smarty implements RenderInterface
{
    use Singleton;
    /** @var \Smarty 模板引擎实例 */
    private $smarty;
    /** @var array 模板变量 */
    protected $data = [];

    public function __construct()
    {
        $this->smarty = new \Smarty();
        $this->smarty->setCompileDir(EASYSWOOLE_ROOT.'/Temp/compile_s/');
        $this->smarty->setCacheDir(EASYSWOOLE_ROOT.'/Temp/cache_s/');
        $this->smarty->setTemplateDir(EASYSWOOLE_ROOT.'/App/View/');
    }

    /**
     * 模板渲染
     * @param string $template  模板文件名或者内容
     * @param array $data  模板输出变量
     * @param array $options 模板参数 TODO:待完善
     *
     * @return string|null
     * @throws \SmartyException
     * @author Dong.cx 2019-07-24 09:59
     * @version V4.0.1
     */
    public function render(string $template, array $data = [], array $options = []): ?string
    {
       foreach ($data as $key => $item) {
           $this->smarty->assign($key, $item);
       }
       return $this->smarty->fetch($template);
    }

    /**
     * 每次渲染完成都会执行清理,可在此处进行变量释放清理等操作
     * @param string|null $result
     * @param string $template
     * @param array $data
     * @param array $options
     *
     * @return mixed
     * @author Dong.cx 2019-07-24 09:50
     * @version V4.0.1
     */
    public function afterRender(?string $result, string $template, array $data = [], array $options = [])
    {
        // TODO: Implement afterRender() method.

    }

    /**
     * 异常处理
     * @param \Throwable $throwable
     *
     * @return string
     * @throws \Throwable
     * @author Dong.cx 2019-07-24 10:01
     * @version V4.0.1
     */
    public function onException(\Throwable $throwable): string
    {
        $msg = "{$throwable->getMessage()} at file:{$throwable->getFile()} line:{$throwable->getLine()}";
        trigger_error($msg);
    }

    /**
     * 模板变量赋值
     * @param mixed $name 变量名
     * @param mixed $value 变量值
     *
     * @return $this
     * @author Dong.cx 2019-07-24 13:50
     * @version V4.0.1
     */
    public function assign($name, $value)
    {
        if (is_array($name)) {
            $this->data = array_merge($this->data, $name);
        } else {
            $this->data[$name] = $value;
        }
        return $this;
    }

    /**
     * 渲染模板数据
     * @param string $template  模板文件名或者内容
     * @param array $data  模板输出变量
     * @param array $options 模板参数
     *
     * @return string|null
     * @author Dong.cx 2019-07-24 13:58
     * @version V4.0.1
     */
    public function fetch(string $template, array $data = [], array $options = [])
    {
        $data = array_merge($this->data, $data);
        return Render::getInstance()->render($template, $data, $options);
    }

    /**
     * 重启渲染引擎
     * 重新渲染模板文件
     * @author Dong.cx 2019-07-24 14:36
     * @version V4.0.1
     */
    public function reload()
    {
        Render::getInstance()->restartWorker();
    }

}