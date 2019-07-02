<?php

class ProcAutoloader
{
    /**
     * [__construct description]
     * 
     */
    public function __construct()
    {
        spl_autoload_register(array($this, '_loader'));
    }

    /**
     * [_loader description]
     * 
     * @param string $className Class name
     * 
     * @return void
     */
    private function _loader($className)
    {
        if (is_file(__DIR__ . '/' . $className . '.php')) {
            include_once __DIR__ . '/' . $className . '.php';
        }
    }

}