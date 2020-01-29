<?php

declare(strict_types=1);

namespace O\Result;

class Err extends Result
{
    /**
     * Err constructor
     *
     * @param mixed $error
     */
    public function __construct($error)
    {
        parent::__construct(null, $error);
    }
}
