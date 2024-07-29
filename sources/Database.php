<?php

/**
 * MySQL adapter for a query builder.
 * php version 8.3.20
 *
 * @category Testing_Tools
 * @author   FunPay team & Tauraloke <tauraloke@gmail.com>
 * @license  Test task
 * @link     https://career.habr.com/vacancies/1000140914
 */

namespace FpDbTest;

use Exception;
use mysqli;
use FpDbTest\Values\ValueFabric;
use FpDbTest\Values\SkippingValue;
use FpDbTest\Exceptions\ParseException;
use FpDbTest\Exceptions\SpecifierTypeException;

/**
 * Class of MySQL adapter for a query builder.
 *
 * @package FpDbTest
 */
class Database implements DatabaseInterface
{
    /**
     * Database instance
     */
    private mysqli $mysqli;

    /**
     * Create a query builder
     *
     * @param mysqli $mysqli Database instance to init
     */
    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * Build a query from the template
     *
     * @param string  $query The template
     * @param mixed[] $args  Some data for substitution into the template
     *
     * @throws Exception
     *
     * @return string
     */
    public function buildQuery(string $query, array $args = []): string
    {
        $specifierSchema = [
            '?' => [
                'quote' => "'",
                'allowed_types' => ['Array', 'Boolean', 'Float', 'Integer', 'Null', 'Skipping', 'String']
            ],
            '?#' =>  [
                'quote' => "`",
                'allowed_types' => ['Array', 'Skipping', 'String']],
            '?a' =>  [
                'quote' => "'",
                'allowed_types' => ['Array', 'String']
            ],
            '?d' =>  [
                'quote' => "",
                'allowed_types' => ['Boolean', 'Integer', 'Null', 'Skipping']
            ],
            '?f' =>  [
                'quote' => "",
                'allowed_types' =>  ['Boolean', 'Integer', 'Float', 'Null', 'Skipping']
            ]
        ];

        $substutues = array_map(fn($a) => ValueFabric::generate($a), $args);

        $specifiers = array_keys($specifierSchema);
        $tokens = preg_split(
            '/([' . join('|', $specifiers) . ']+)/',
            $query,
            -1,
            PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
        );
        $subCounter = 0;
        $currentCursor = 0;
        $positionsToSkipCondition = [];


        foreach ($tokens as $key => $token) {
            $currentCursor += strlen($token);
            if (isset($specifierSchema[$token])) { // current token is control
                if (!isset($substutues[$subCounter])) {
                    throw new ParseException(
                        'Not enough arguments for the expression!'
                    );
                }
                $argClassType = $substutues[$subCounter]->getType();
                if (!in_array($argClassType, $specifierSchema[$token]['allowed_types'])) {
                    throw new SpecifierTypeException(
                        "Forbidden type {$argClassType} for specifier {$token}"
                    );
                }
                $tokens[$key] = $substutues[$subCounter]->serialize(
                    $specifierSchema[$token]['quote']
                );
                if ($substutues[$subCounter]->isSkipping()) {
                    $positionsToSkipCondition[] = $currentCursor;
                }
                $subCounter++;
                continue;
            }
        }
        $result = join('', $tokens);

        foreach ($positionsToSkipCondition as $skippingPosition) {
            $result = $this->cutOut($result, $skippingPosition);
        }
        $result = $this->cutFreeConditions($result);

        return $result;
    }


    /**
     * Generate a control structure for conditional blocks
     *
     * @return SkippingValue
     */
    public function skip()
    {
        return new SkippingValue();
    }

    /**
     * Remove curly brackets to left and to right from the position.
     *
     * @param string $str      Preparing string
     * @param int    $position Cursor to erase brackets.
     *
     * @return string
     */
    private function cutOut(string $str, int $position)
    {
        $startToCut = null;
        $openedQuote = false;
        for ($i = $position; $i >= 0; $i--) {
            if ($str[$i] === "'") {
                $openedQuote = !$openedQuote;
            } elseif ($str[$i] === '{') {
                if (!$openedQuote) {
                    $startToCut = $i;
                    break;
                }
            }
        }

        $endToCut = null;
        $openedQuote = false;
        for ($i = $position; $i < strlen($str); $i++) {
            if ($str[$i] === "'") {
                $openedQuote = !$openedQuote;
            } elseif ($str[$i] === '}') {
                if (!$openedQuote) {
                    $endToCut = $i;
                    break;
                }
            }
        }

        return substr($str, 0, $startToCut) . substr($str, $endToCut + 1);
    }
    /**
     * Remove free curly brackets.
     *
     * @param string $str Preparing string
     *
     * @return string
     */
    private function cutFreeConditions(string $str): string
    {
        $letters = [];
        $startbrackets = false;
        $startQuote = false;
        for ($i = 0; $i < strlen($str); $i++) {
            if ($str[$i] === '{' && !$startbrackets && !$startQuote) {
                $startbrackets = true;
                continue;
            }
            if ($str[$i] === '}' && $startbrackets && !$startQuote) {
                $startbrackets = false;
                continue;
            }
            if ($str[$i] === "'") {
                $startQuote = !$startQuote;
            }
            $letters[] = $str[$i];
        }

        return join("", $letters);
    }
}
