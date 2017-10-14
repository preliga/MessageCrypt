<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 2017-10-01
 * Time: 19:46
 */

namespace AppBundle\Ratchet\Server;


use AppBundle\Ratchet\Client;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


use React\EventLoop\LoopInterface;

class Server implements MessageComponentInterface
{

    protected $connections = [];

    protected $container;

    protected $clients = [];

    public function __construct(ContainerInterface $container, LoopInterface $loop)
    {
        $this->container = $container;

        // Breathe life into the game
        $loop->addPeriodicTimer(3, function()
        {
            foreach ($this->clients as $client){
                $messages = $client->getUsersToMessages();
                $invitations = $client->getInvitations();

                $client->getConnection()->send(json_encode(
                        [
                            'messages' => json_encode($messages),
                            'invitations' => json_encode($invitations)
                        ]
                    )
                );
            }
        });
    }

    /**
     * A new websocket connection
     *
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $this->connections[] = $connection;
        echo "New connection \n";
    }

    /**
     * Handle message sending
     *
     * @param ConnectionInterface $connection
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $connection, $msg)
    {
        $messageData = json_decode(trim($msg));

        $this->clients[$messageData->authToken] = new Client($this->container, $connection, $messageData->authToken);
    }

    /**
     * A connection is closed
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection)
    {
        foreach($this->connections as $key => $conn_element){
            if($connection === $conn_element){
                unset($this->connections[$key]);
                break;
            }
        }
    }

    /**
     * Error handling
     *
     * @param ConnectionInterface $connection
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $connection->send("Error : " . $e->getMessage());
        $connection->close();
    }
}