<?php namespace Zalazdi\LaravelImap;

use Illuminate\Support\Facades\Config;

use Zalazdi\LaravelImap\Client;
use Zalazdi\LaravelImap\Exceptions\ConnectionFailedException;
use Zalazdi\LaravelImap\Exceptions\GetMessagesFailedException;

class Mailbox
{
    public $client;
    public $mailbox;
    public $name;

    function __construct(Client $client, $mailbox, $name)
    {
        $this->client = $client;
        $this->mailbox = $mailbox;
        $this->name = $name;
    }

    public function getMessages($criteria = 'ALL')
    {
        return $this->client->getMessages($this, $criteria);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMailbox()
    {
        return $this->mailbox;
    }
}