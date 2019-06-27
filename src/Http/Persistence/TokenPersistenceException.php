<?php

namespace Shopgate\ConnectSdk\Http\Persistence;

use Shopgate\ConnectSdk\Exception\Exception;

class TokenPersistenceException extends Exception
{
    /** @var \Exception[] */
    private $previousExceptions;

    public function __construct($message = '', array $previous = [])
    {
        $this->previousExceptions = $previous;
        $prev = array_values($previous);
        parent::__construct($message, 0, end($prev));
    }

    /**
     * @return \Exception[]
     */
    public function getPreviousExceptions()
    {
        return $this->previousExceptions;
    }
}
