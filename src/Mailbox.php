<?php

namespace Zalazdi\LaravelImap;

class Mailbox
{
    /**
     * Client instance
     *
     * @var \Zalazdi\LaravelImap\Client
     */
    protected $client;

    /**
     * Mailbox full path
     *
     * @var string
     */
    public $path;

    /**
     * Mailbox name
     *
     * @var string
     */
    public $name;

    /**
     * Mailbox fullname
     *
     * @var string
     */
    public $fullName;

    /**
     * Children mailboxes
     *
     * @var array
     */
    public $children = [];

    /**
     * Delimiter for mailbox
     *
     * @var string
     */
    public $delimiter;

    /**
     * Indicates if mailbox can't containg any "children".
     * CreateMailbox won't work on this mailbox.
     *
     * @var boolean
     */
    public $no_inferiors;

    /**
     * Indicates if mailbox is only container, not a mailbox - you can't open it.
     *
     * @var boolean
     */
    public $no_select;

    /**
     * Indicates if mailbox is marked. This means that it may contain new messages since the last time it was checked.
     * Not provided by all IMAP servers.
     *
     * @var boolean
     */
    public $marked;

    /**
     * Indicates if mailbox containg any "children".
     * Not provided by all IMAP servers.
     *
     * @var boolean
     */
    public $has_children;

    /**
     * Indicates if mailbox refers to other mailbox.
     * Not provided by all IMAP servers.
     *
     * @var boolean
     */
    public $referal;

    /**
     * Mailbox constructor.
     *
     * @param \Zalazdi\LaravelImap\Client $client
     * @param $mailbox
     */
    public function __construct(Client $client, $mailbox)
    {
        $this->client = $client;

        $this->delimiter = $mailbox->delimiter;
        $this->path = $mailbox->name;
        $this->fullName = $this->decodeName($mailbox->name);
        $this->name = $this->getSimpleName($this->delimiter, $this->fullName);

        $this->parseAttributes($mailbox->attributes);
    }

    /**
     * Determine if mailbox has children.
     *
     * @return bool
     */
    public function hasChildren()
    {
        return $this->has_children;
    }

    /**
     * Set children.
     *
     * @param array $children
     */
    public function setChildren($children = [])
    {
        $this->children = $children;
    }

    /**
     * Get messages.
     *
     * @param string $criteria
     *
     * @return array
     */
    public function getMessages($criteria = 'ALL')
    {
        return $this->client->getMessages($this, $criteria);
    }

    /**
     * Decode name.
     * It converts UTF7-IMAP encoding to UTF-8.
     *
     * @param $name
     *
     * @return mixed|string
     */
    protected function decodeName($name)
    {
        preg_match('#\{(.*)\}(.*)#', $name, $preg);
        return mb_convert_encoding($preg[2], "UTF-8", "UTF7-IMAP");
    }

    /**
     * Get simple name (without parent folders).
     *
     * @param $delimiter
     * @param $fullName
     *
     * @return mixed
     */
    protected function getSimpleName($delimiter, $fullName)
    {
        $arr = explode($delimiter, $fullName);

        return end($arr);
    }

    /**
     * Parse attributes and set it to object properties.
     *
     * @param $attributes
     */
    protected function parseAttributes($attributes)
    {
        $this->no_inferiors = ($attributes & LATT_NOINFERIORS)  ? true : false;
        $this->no_select    = ($attributes & LATT_NOSELECT)     ? true : false;
        $this->marked       = ($attributes & LATT_MARKED)       ? true : false;
        $this->referal      = ($attributes & LATT_REFERRAL)     ? true : false;
        $this->has_children = ($attributes & LATT_HASCHILDREN)  ? true : false;
    }
}