<?php

/**
 * Defining interface for typed values of arguments of a building query.
 * php version 8.3.20
 *
 * @category Testing_Tools
 * @package  FpDbTest
 * @author   FunPay team & Tauraloke <tauraloke@gmail.com>
 * @license  Test task
 * @link     https://career.habr.com/vacancies/1000140914
 */

namespace FpDbTest\Values;

/**
 * Interface of typed values for the query expression.
 *
 * @package FpDbTest
 */
abstract class AbstractValue
{
    protected mixed $value;
    /**
     * Construct the object
     *
     * @param mixed $value One of input arguments of the building query.
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }
    /**
     * Flat the object to a string.
     *
     * @param string $quotes Type of quotes for serialization.
     *
     * @return string
     */
    abstract public function serialize(string $quotes = "'"): string;
    /**
     * Inspect for a type.
     *
     * @return string Type of a value.
     */
    public function getType(): string
    {
        $path = explode('\\', get_class($this));
        return str_replace('Value', '', array_pop($path));
    }
    /**
     * Fast check for a control object.
     *
     * @return bool
     */
    public function isSkipping(): bool
    {
        return false;
    }
}
