<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 09/06/2017
 * Time: 15:42
 */

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