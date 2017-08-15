<?php

namespace Empari\Laravel\AntiSpam\Exceptions;


/**
 * FailedToMarkAsSpamException class
 */
class FailedToMarkAsSpamException extends \Exception
{
    protected $message = 'Failed to mark as spam.';
}