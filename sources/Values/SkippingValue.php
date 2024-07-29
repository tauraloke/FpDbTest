<?php

/**
 * Defining empty util token object to reject conditions in the query expression.
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
 * Empty util token to reject conditions in the query expression.
 *
 * @package FpDbTest
 */
class SkippingValue extends AbstractValue
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
        return '';
    }
    /**
     * Fast check for a control object.
     *
     * @return bool
     */
    public function isSkipping(): bool
    {
        return true;
    }
}
