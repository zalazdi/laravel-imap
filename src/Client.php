<?php namespace Zalazdi\LaravelImap;

use Illuminate\Support\Facades\Config;

use Zalazdi\LaravelImap\Exceptions\ConnectionFailedException;
use Zalazdi\LaravelImap\Exceptions\GetMessagesFailedException;

class Client
{
    public $connection = false;
    public $resource;

    public $host;
    public $port;
    public $encryption;
    public $validate_cert;
    public $read_only;
    public $username;
    public $password;

    protected $mailboxes = [];
    protected $currentMailbox = '';

    public function __construct($mailbox = '')
    {
        $this->host = Config::get('imap.host');
        $this->port = Config::get('imap.port');
        $this->encryption = Config::get('imap.encryption');
        $this->validate_cert = Config::get('imap.validate_cert');
        $this->read_only = Config::get('imap.read_only');
        $this->username = Config::get('imap.username');
        $this->password = Config::get('imap.password');
    }

    public function connect($mailboxName = '', $attempts = 3)
    {
        $this->resource = $this->getAddress($mailboxName);

        if ($this->connection != false) {
            $this->disconnect();
        }

        $options = ($this->read_only) ? OP_READONLY : 0;

        try {
            $this->connection = imap_open($this->resource, $this->username, $this->password, $options, $attempts);
        } catch(\ErrorException $e) {
            $message = $e->getMessage().'. '.implode("; ", imap_errors());

            throw new ConnectionFailedException($message);
        }

        return $this;
    }

    public function disconnect()
    {
        if($this->connection != false) {
            imap_close($this->connection);
        }

        return $this;
    }

    public function getMailboxes()
    {
        if (empty($this->mailboxes)) {
            $mailboxes = imap_getmailboxes($this->connection, $this->resource, '*');

            foreach ($mailboxes as $item) {

                preg_match('#\{(.*)\}(.*)#', $item->name, $name);
                $mailbox = $name[0];
                $name = $name[2];

                $this->mailboxes[$name] = new Mailbox($this, $mailbox, $name);
            }
        }

        return $this->mailboxes;
    }

    public function openMailbox(Mailbox $mailbox)
    {
        $this->currentMailbox = $mailbox;
        $options = ($this->read_only) ? OP_READONLY : 0;

        imap_reopen($this->connection, $mailbox->getMailbox(), $options, 3);
    }

    public function getMessages(Mailbox $mailbox,$criteria = 'ALL')
    {
        try
        {
            $this->openMailbox($mailbox);
            $messages = [];
            $availableMessages = imap_search($this->connection, $criteria, SE_UID);

            if ($availableMessages) {
                foreach ($availableMessages as $msgno) {
                    $message = new Message($this, $msgno);

                    $messages[$message->message_id] = $message;
                }
            }
            return $messages;
        }
        catch(\Exception $e)
        {
            $message = $e->getMessage();

            throw new GetMessagesFailedException($message);
        }
    }


    protected function getAddress($mailboxName = false)
    {
        $address = "{".$this->host.":".$this->port."/imap";
        if (!$this->validate_cert)
            $address .= '/novalidate-cert';
        if ($this->encryption == 'ssl')
            $address .= '/ssl';
        $address .= '}';

        if ($mailboxName)
            $address .= '/';

        return $address;
    }
}
