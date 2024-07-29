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
 * Typed ArrayValue value for the query expression.
 *
 * @package FpDbTest
 */
class ArrayValue extends AbstractValue
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
        if (array_is_list($this->value)) {
            return join(
                ', ',
                array_map(
                    fn($a) => ValueFabric::generate($a)->serialize($quotes),
                    $this->value
                )
            );
        } else {
            return join(
                ', ',
                array_map(
                    fn($key, $a) => '`' . $key . '`' . ' = ' . ValueFabric::generate($a)->serialize($quotes),
                    array_keys($this->value),
                    $this->value
                )
            );
        }
    }
}
