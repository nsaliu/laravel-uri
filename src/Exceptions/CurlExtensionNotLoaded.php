<?php

namespace Nsaliu\Uri\Exceptions;

class CurlExtensionNotLoaded extends \Exception
{
    /**
     * @var string
     */
    protected $message = 'cURL extension is not loaded';
}
