<?php

/**
 * Defining fabric to create value objects from the argument.
 * php version 8.3.20
 *
 * @category Testing_Tools
 * @package  FpDbTest
 * @author   FunPay team & Tauraloke <tauraloke@gmail.com>
 * @license  Test task
 * @link     https://career.habr.com/vacancies/1000140914
 */

namespace FpDbTest\Values;

use FpDbTest\Exceptions\ArgumentTypeException;

/**
 * Interface of typed values for the query expression.
 *
 * @package FpDbTest
 */
class ValueFabric
{
    public const SKIPPING_CLASS_NAME = "FpDbTest\\Values\\SkippingValue";
    /**
     * Create the value object around raw value.
     *
     * @param mixed    $value
     * @param callable $sanitize
     *
     * @throws ArgumentTypeException
     *
     * @return \FpDbTest\Values\AbstractValue
     */
    public static function generate(mixed $value): AbstractValue
    {
        switch ($type = gettype($value)) {
            case 'string':
                return new StringValue($value);
            case 'integer':
                return new IntegerValue($value);
            case 'double':
                return new FloatValue($value);
            case 'array':
                return new ArrayValue($value);
            case 'boolean':
                return new BooleanValue($value);
            case 'NULL':
                return new NullValue();
            case 'object':
                $className = get_class($value);
                if ($className == ValueFabric::SKIPPING_CLASS_NAME) {
                    return $value;
                } else {
                    throw new ArgumentTypeException(
                        "Unknown class {$className} of argument: " . json_encode($value)
                    );
                }
                // Else go to the default case.
            default:
                throw new ArgumentTypeException(
                    "Unsupportable type {$type} of argument: " . json_encode($value)
                );
        }
    }
}
