<?php


namespace Myhayo\Walle;


use Illuminate\Config\Repository;

class WalleService
{
    private $app_config;

    public function __construct(Repository $config)
    {
        $this->app_config = $config->get('walle');
    }

    
}