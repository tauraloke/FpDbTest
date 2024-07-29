<?php

/**
 * Defining query exceptions
 * php version 8.3.20
 *
 * @category Testing_Tools
 * @package  FpDbTest
 * @author   FunPay team & Tauraloke <tauraloke@gmail.com>
 * @license  Test task
 * @link     https://career.habr.com/vacancies/1000140914
 */

namespace FpDbTest\Exceptions;

use Exception;

/**
 * Exception for wrong type of an argument for the query.
 *
 * @package FpDbTest
 */
class ArgumentTypeException extends Exception
{
}
