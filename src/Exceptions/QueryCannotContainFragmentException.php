<?php

namespace Nsaliu\Uri\Exceptions;

class QueryCannotContainFragmentException extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'Query can not contains fragment parts';
}
