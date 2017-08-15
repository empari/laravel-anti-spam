<?php
namespace Empari\Laravel\AntiSpam\Traits;

use Empari\Laravel\AntiSpam\Exceptions\NullColumnException;
use Empari\Laravel\AntiSpam\Spam;

trait Spammable
{
    /**
     * Get Spam Columns
     *
     * @return array
     */
    public function getSpamColumns()
    {
        return [
            'author' => 'name',
            'author_email' => 'email',
        ];
    }

    /**
     * Verify if Model is Spam
     *
     * @param array $additional
     * @return bool
     */
    public function isSpam(array $additional = [])
    {
        return Spam::isSpam($this->getSpamColumnValues(), $additional);
    }

    public function markAsSpam(array $additional = [])
    {
        return Spam::markAsSpam($this->getSpamColumnValues(), $additional);
    }

    public function markAsHam(array $additional = [])
    {
        return Spam::markAsHam($this->getSpamColumnValues(), $additional);
    }

    protected function getSpamColumnValues()
    {
        $modelArray = $this->toArray();

        if (count($this->getSpamColumns()) < 1) {
            throw new NullColumnException();
        }

        return array_filter(array_map(function($column) use ($modelArray) {
            return array_get($modelArray, $column);
        }, $this->getSpamColumns()));
    }
}