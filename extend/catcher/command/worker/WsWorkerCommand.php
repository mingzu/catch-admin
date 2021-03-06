<?php
declare (strict_types = 1);

namespace catcher\command\worker;

use catcher\CatchAdmin;
use think\Config;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;
use Workerman\Worker;

class WsWorkerCommand extends Command
{
    protected $address = '127.0.0.1:10001';

    protected function configure()
    {
        // 指令配置
        $this->setName('ws:server')
            ->addArgument('option', Argument::OPTIONAL, '[start|reload|stop|restart|reload|status|connections]', 'start')
            ->addOption('mode', '-m', Option::VALUE_REQUIRED, 'worker start mode')
            ->addOption('number', '-n', Option::VALUE_REQUIRED, 'worker number')
            ->addOption('address', '-a',Option::VALUE_REQUIRED, 'listen address, like \'127.0.0.1:9090\'')
            ->setDescription('start websocket server, default listen 127.0.0.1 port 10001');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->setWokrermanCommnd();

        $this->start();
    }

    /**
     * worker start
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/23
     * @return void
     */
    protected function start()
    {
        $ws = new Worker(sprintf('websocket://%s', $this->getAddress()));

        $ws->count = $this->getWorkerNumber();

        $ws->runAll();
    }

    /**
     * @return string
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/23
     */
    protected function getAddress()
    {
        return $this->input->getOption('address') ? : $this->address;
    }

    /**
     * worker number
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/23
     * @return mixed
     */
    protected function getWorkerNumber()
    {
        return $this->input->getOption('number') ? : 3;
    }

    /**
     * set workerman command
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/23
     * @return void
     */
    protected function setWokrermanCommnd()
    {
        global $argv;

        $option = $this->input->getArgument('option');

        $mode = $this->input->getOption('mode');

        if ($option) {
            array_unshift($argv, $mode);
        }

        array_unshift($argv, $option);

        array_unshift($argv, 'catchWorker');
    }
}
