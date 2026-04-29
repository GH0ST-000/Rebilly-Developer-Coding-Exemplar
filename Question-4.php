<?php

/**
 * Write a complete set of unit tests for one of the following code blocks. Describe how the approach might change for the other language.
 */
declare(strict_types=1);

if (!function_exists('fizzBuzz')) {
    function fizzBuzz($start = 1, $stop = 100)
    {
        $string = '';

        if ($stop < $start || $start < 0 || $stop < 0) {
            throw new InvalidArgumentException();
        }

        for ($i = $start; $i <= $stop; $i++) {
            if ($i % 3 == 0 && $i % 5 == 0) {
                $string .= 'FizzBuzz';
                continue;
            }

            if ($i % 3 == 0) {
                $string .= 'Fizz';
                continue;
            }

            if ($i % 5 == 0) {
                $string .= 'Buzz';
                continue;
            }

            $string .= $i;
        }

        return $string;
    }
}

/*
 * How the approach changes for the JavaScript version
 * ----------------------------------------------------
 * Same equivalence-class plan, different bindings. With Vitest:
 *
 *   import { describe, it, expect } from 'vitest';
 *   import { fizzBuzz } from './fizzBuzz.js';
 *
 *   describe('fizzBuzz', () => {
 *       it('uses defaults 1..100 when called with no args', () => {
 *           expect(fizzBuzz()).toBe(fizzBuzz(1, 100));
 *       });
 *
 *       it('produces the canonical 1..15 cycle', () => {
 *           expect(fizzBuzz(1, 15)).toBe('12Fizz4BuzzFizz78FizzBuzz11Fizz1314FizzBuzz');
 *       });
 *
 *       it.each([
 *           [0, 'FizzBuzz'], [1, '1'], [3, 'Fizz'], [5, 'Buzz'], [15, 'FizzBuzz'], [100, 'Buzz'],
 *       ])('classifies %i as %s', (n, expected) => {
 *           expect(fizzBuzz(n, n)).toBe(expected);
 *       });
 *
 *       it.each([[10, 1], [-1, 10], [-5, -1]])('throws on (%i, %i)', (start, stop) => {
 *           expect(() => fizzBuzz(start, stop)).toThrow(Error);
 *       });
 *   });
 *
 * What changes vs PHP:
 *
 *   1. Exception specificity. PHPUnit matches InvalidArgumentException by class. The JS function throws a
 *      generic Error('Invalid arguments'), so the strongest assertion available is a message regex. The
 *      principal-engineer recommendation is to make the JS function throw a custom InvalidArgumentError
 *      (extends Error) so tests can match by class — same precision PHP gets for free.
 *
 *   2. Equality. PHPUnit's assertSame is === strict; Vitest's toBe uses Object.is, equivalent for primitives.
 *
 *   3. Test discovery. PHPUnit derives the test class name from the filename. Vitest auto-runs *.test.{js,ts}
 *      colocated with the source, so the JS tests would live in fizzBuzz.test.js with no naming gymnastics.
 *
 *   4. Type coercion. PHP function has no parameter types, JS has none at runtime. In both, the right move
 *      is to tighten the signature (PHP `int`, TypeScript `number`) rather than test implicit coercion.
 *
 *   5. Modulo and zero behave identically across both languages, so all classification tests transfer 1:1.
 */
