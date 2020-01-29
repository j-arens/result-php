<?php

declare(strict_types=1);

namespace O\Result;

use O\Option\{OptionInterface, Some, None};

class Result implements ResultInterface
{
    /**
     * @var mixed
     */
    protected $success;

    /**
     * @var mixed
     */
    protected $error;

    /**
     * Result constructor
     *
     * @param mixed $success
     * @param mixed $error
     */
    public function __construct($success, $error)
    {
        if (!$this->isOk() && !$this->isErr()) {
            throw ResultException::illegalInstantiation();
        }
        $this->success = $success;
        $this->error = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function isOk(): bool
    {
        return $this instanceof Ok;
    }

    /**
     * {@inheritdoc}
     */
    public function isErr(): bool
    {
        return $this instanceof Err;
    }

    /**
     * {@inheritdoc}
     */
    public function ok(): OptionInterface
    {
        if ($this->isOk()) {
            return new Some($this->success);
        }
        return new None();
    }

    /**
     * {@inheritdoc}
     */
    public function err(): OptionInterface
    {
        if ($this->isOk()) {
            return new None();
        }
        return new Some($this->error);
    }

    /**
     * {@inheritdoc}
     */
    public function and(ResultInterface $res): ResultInterface
    {
        if ($this->isOk()) {
            return $res;
        }
        return new Err($this->error);
    }

    /**
     * {@inheritdoc}
     */
    public function andThen(callable $op): ResultInterface
    {
        if ($this->isOk()) {
            return $op($this->success);
        }
        return new Err($this->error);
    }

    /**
     * {@inheritdoc}
     */
    public function expect(string $msg)
    {
        if ($this->isOk()) {
            return $this->success;
        }
        throw new ResultException($msg);
    }

    /**
     * {@inheritdoc}
     */
    public function expectErr(string $msg)
    {
        if ($this->isErr()) {
            return $this->error;
        }
        throw new ResultException($msg);
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $op): ResultInterface
    {
        if ($this->isOk()) {
            return new Ok($op($this->success));
        }
        return new Err($this->error);
    }

    /**
     * {@inheritdoc}
     */
    public function mapErr(callable $op): ResultInterface
    {
        if ($this->isOk()) {
            return new Ok($this->success);
        }
        return new Err($op($this->error));
    }

    /**
     * {@inheritdoc}
     */
    public function or(ResultInterface $res): ResultInterface
    {
        if ($this->isOk()) {
            return new Ok($this->success);
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function orElse(callable $op): ResultInterface
    {
        if ($this->isOk()) {
            return new Ok($this->success);
        }
        return $op($this->error);
    }

    /**
     * {@inheritdoc}
     */
    public function unwrap()
    {
        if ($this->isOk()) {
            return $this->success;
        }
        throw ResultException::illegalCall('unwrap', 'Err');
    }

    /**
     * {@inheritdoc}
     */
    public function unwrapErr()
    {
        if ($this->isErr()) {
            return $this->error;
        }
        throw ResultException::illegalCall('unwrapErr', 'Ok');
    }

    /**
     * {@inheritdoc}
     */
    public function unwrapOr($optb)
    {
        if ($this->isOk()) {
            return $this->success;
        }
        return $optb;
    }

    /**
     * {@inheritdoc}
     */
    public function unwrapOrElse(callable $op)
    {
        if ($this->isOk()) {
            return $this->success;
        }
        return $op($this->error);
    }
}
