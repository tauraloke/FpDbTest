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
 * Typed string value for the query expression.
 * Sanitized when serialized.
 *
 * @package FpDbTest
 */
class StringValue extends AbstractValue
{
    /**
     * Flat the object to a string.
     *
     * @param string $quotes Framing quotes around the text.
     *
     * @return string
     */
    public function serialize(string $quotes = "'"): string
    {
        return $quotes . addslashes($this->value) . $quotes;
    }
}
