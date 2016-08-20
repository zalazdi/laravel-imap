<?php

namespace Zalazdi\LaravelImap\Facades;

use Illuminate\Support\Facades\Facade;
use Zalazdi\LaravelImap\ClientManager;

class Client extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ClientManager::class;
    }
}