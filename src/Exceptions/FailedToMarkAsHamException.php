<?php

namespace Empari\Laravel\AntiSpam\Exceptions;


/**
 * Class FailedToMarkAsHamException
 * @package Naweby\Support\Utils\Spam\Exceptions
 */
class FailedToMarkAsHamException extends \Exception
{
    protected $message = 'Failed to mark as ham.';
}