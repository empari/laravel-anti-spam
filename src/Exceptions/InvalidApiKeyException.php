<?php

namespace Empari\Laravel\AntiSpam\Exceptions;

/**
 * InvalidApiKeyException class
 */
class InvalidApiKeyException extends \Exception
{
    protected $message = 'Your service API key is invalid.';
}