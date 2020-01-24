<?php

namespace Nsaliu\Uri\Exceptions;

use Throwable;

class PortOutOfRangeException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'A valid port must be between 0 and 65535, [%s] given';

    /**
     * PortOutOfRangeException constructor.
     *
     * @param int            $port
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(int $port, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf($this->message, $port), $code, $previous);
    }
}
