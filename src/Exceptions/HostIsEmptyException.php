<?php

namespace Nsaliu\Uri\Exceptions;

class HostIsEmptyException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'The host is empty';
}
