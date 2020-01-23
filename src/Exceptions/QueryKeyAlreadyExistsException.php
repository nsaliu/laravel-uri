<?php

namespace Nsaliu\Uri\Exceptions;

use Throwable;

class QueryKeyAlreadyExistsException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Given query key [%s] already exists';

    /**
     * QueryKeyAlreadyExists constructor.
     * @param string $key
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $key, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf($this->message, $key), $code, $previous);
    }
}
