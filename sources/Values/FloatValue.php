<?php

/**
 * Defining typed value of argument for a building query
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
 * Typed Double (float) value for the query expression.
 *
 * @package FpDbTest
 */
class FloatValue extends AbstractValue
{
    /**
     * Flat the object to a string.
     *
     * @param string $quotes Type of quotes for serialization.
     *
     * @return string
     */
    public function serialize(string $quotes = "'"): string
    {
        return strval($this->value);
    }
}
