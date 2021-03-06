<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-10-01
 * Time: 19:44
 */

namespace AppBundle\Ratchet\Command;


use AppBundle\Ratchet\Server\Server;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server as Reactor;

class ServerCommand extends ContainerAwareCommand
{

    /**
     * Configure a new Command Line
     */
    protected function configure()
    {
        $this
            ->setName('Project:notification:server')
            ->setDescription('Start the notification server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop = LoopFactory::create();
        $socket = new Reactor('127.0.0.1:8080', $loop);

        $server = new IoServer(
            new HttpServer(
                new WsServer(
                    new Server(
                        $this->getContainer(),
                        $loop
                    )
                )
            ),
            $socket,
            $loop
        );

        $server->run();

    }

//    public function tick

}