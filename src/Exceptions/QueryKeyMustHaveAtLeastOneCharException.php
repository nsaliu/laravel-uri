<?php

namespace Nsaliu\Uri\Exceptions;

class QueryKeyMustHaveAtLeastOneCharException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Query key must have at least one character';
}
