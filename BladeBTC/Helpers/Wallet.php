<?php

namespace BladeBTC\Helpers;

use Blockchain\Blockchain;

class Wallet
{

    private $_API = null;

    /**
     * Wallet constructor.
     */
    public function __construct()
    {
        $this->_API = new Blockchain();
    }
}