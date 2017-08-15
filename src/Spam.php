<?php
namespace Empari\Laravel\AntiSpam;

use Illuminate\Support\Facades\Facade;
use Empari\Laravel\AntiSpam\Services\SpamServiceInterface;

class Spam extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SpamServiceInterface::class;
    }
}