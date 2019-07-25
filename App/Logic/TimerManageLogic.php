<?php
/**
 * Description:
 * Created by Dong.cx
 * DateTime: 2019-07-25 10:46
 * @version V4.0.1
 */

namespace App\Logic;


use EasySwoole\Http\Message\Status;
use EasySwoole\Rpc\AbstractService;

class TimerManageLogic extends AbstractService
{
    /**
     *
     * @return string
     * @author Dong.cx 2019-07-25 10:47
     * @version V4.0.1
     */
    public function serviceName(): string
    {
        // TODO: Implement serviceName() method.
        return 'timer';
    }

    public function run()
    {
        $arg = $this->request()->getArg();
        var_dump($arg);
        $this->response()->setResult(['uid'=>22]);
        $this->response()->setStatus(Status::CODE_OK);
        $this->response()->setMsg('this is running');
    }

    public function addTimerTask()
    {

    }
}