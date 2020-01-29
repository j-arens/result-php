<?php

declare(strict_types=1);

namespace O\Result;

use O\Option\OptionInterface;

interface ResultInterface
{
    /**
     * Returns true if the result is Ok.
     *
     * @return bool
     */
    public function isOk(): bool;

    /**
     * Returns true if the result is Err.
     *
     * @return bool
     */
    public function isErr(): bool;

    /**
     * Converts from Result<T, E> to Option<T>.
     *
     * @return OptionInterface
     */
    public function ok(): OptionInterface;

    /**
     * Converts from Result<T, E> to Option<E>.
     *
     * @return OptionInterface
     */
    public function err(): OptionInterface;

    /**
     * Returns res if the result is Ok, otherwise returns the Err value of self.
     *
     * @param ResultInterface $res
     * @return ResultInterface
     */
    public function and(ResultInterface $res): ResultInterface;

    /**
     * Calls op if the result is Ok, otherwise returns the Err value of self.
     *
     * @param callable $op
     * @return ResultInterface
     */
    public function andThen(callable $op): ResultInterface;

    /**
     * Unwraps a result, yielding the content of an Ok.
     *
     * @param string $msg
     * @return mixed
     */
    public function expect(string $msg);

    /**
     * Unwraps a result, yielding the content of an Err.
     *
     * @param string $msg
     * @return mixed
     */
    public function expectErr(string $msg);

    /**
     * Maps a Result<T, E> to Result<U, E> by applying a function to a contained Ok value,
     * leaving an Err value untouched.
     *
     * @param callable $op
     * @return ResultInterface
     */
    public function map(callable $op): ResultInterface;

    /**
     * Maps a Result<T, E> to Result<T, F> by applying a function to a contained Err value,
     * leaving an Ok value untouched.
     *
     * @param callable $op
     * @return ResultInterface
     */
    public function mapErr(callable $op): ResultInterface;

    /**
     * Returns res if the result is Err, otherwise returns the Ok value of self.
     *
     * @param ResultInterface $res
     * @return ResultInterface
     */
    public function or(ResultInterface $res): ResultInterface;

    /**
     * Calls op if the result is Err, otherwise returns the Ok value of self.
     *
     * @param callable $op
     * @return ResultInterface
     */
    public function orElse(callable $op): ResultInterface;

    /**
     * Unwraps a result, yielding the content of an Ok.
     *
     * @return mixed
     */
    public function unwrap();

    /**
     * Unwraps a result, yielding the content of an Err.
     *
     * @return mixed
     */
    public function unwrapErr();

    /**
     * Unwraps a result, yielding the content of an Ok. Else, it returns optb.
     *
     * @param mixed $optb
     * @return mixed
     */
    public function unwrapOr($optb);

    /**
     * Unwraps a result, yielding the content of an Ok.
     * If the value is an Err then it calls op with its value.
     *
     * @param callable $op
     * @return mixed
     */
    public function unwrapOrElse(callable $op);
}
