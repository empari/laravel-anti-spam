<?php

namespace Empari\Laravel\AntiSpam\Exceptions;

/**
 * FailedToCheckSpamException class
 */
class FailedToCheckSpamException extends \Exception
{
    protected $message = 'Failed to check spam.';
}