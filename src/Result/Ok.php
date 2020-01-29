<?php

declare(strict_types=1);

namespace O\Result;

class Ok extends Result
{
    /**
     * Ok constructor
     *
     * @param mixed $success
     */
    public function __construct($success)
    {
        parent::__construct($success, null);
    }
}
