<?php

declare(strict_types=1);

namespace O\Result;

use Exception;

class ResultException extends Exception
{
    /**
     * Returns a new result exception with illegal instantiation message
     *
     * @return ResultException
     */
    public static function illegalInstantiation(): ResultException
    {
        return new ResultException('Result must be an instance of Ok or Err');
    }

    /**
     * Returns a new result exception with illegal call message
     *
     * @return ResultException
     */
    public static function illegalCall(string $method, string $type): ResultException
    {
        return new ResultException("cannot call $method on a Result of type $type");
    }
}
