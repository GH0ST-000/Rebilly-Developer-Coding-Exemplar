<?php
declare(strict_types=1);

require_once __DIR__ . '/Question-4.php';

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FizzBuzzTest extends TestCase
{
    public function testCallingWithoutArgumentsUsesDefaultsOneThroughOneHundred(): void
    {
        $this->assertSame(fizzBuzz(1, 100), fizzBuzz());
    }

    public function testCanonicalCycleOneThroughFifteen(): void
    {
        $this->assertSame(
            '12Fizz4BuzzFizz78FizzBuzz11Fizz1314FizzBuzz',
            fizzBuzz(1, 15)
        );
    }

    /** @return array<string, array{int, string}> */
    public static function singleNumberProvider(): array
    {
        return [
            'zero is FizzBuzz'    => [0,   'FizzBuzz'],
            'one is plain'        => [1,   '1'],
            'three is Fizz'       => [3,   'Fizz'],
            'five is Buzz'        => [5,   'Buzz'],
            'seven is plain'      => [7,   '7'],
            'fifteen is FizzBuzz' => [15,  'FizzBuzz'],
            'thirty is FizzBuzz'  => [30,  'FizzBuzz'],
            'ninety-nine is Fizz' => [99,  'Fizz'],
            'one hundred is Buzz' => [100, 'Buzz'],
        ];
    }

    #[DataProvider('singleNumberProvider')]
    public function testSingleNumberClassification(int $n, string $expected): void
    {
        $this->assertSame($expected, fizzBuzz($n, $n));
    }

    /** @return array<string, array{int, int, string}> */
    public static function rangeProvider(): array
    {
        return [
            'one to three'        => [1, 3,   '12Fizz'],
            'one to five'         => [1, 5,   '12Fizz4Buzz'],
            'three to five'       => [3, 5,   'Fizz4Buzz'],
            'spans 15'            => [14, 16, '14FizzBuzz16'],
            'starts on Buzz'      => [5, 7,   'BuzzFizz7'],
            'starts at zero'      => [0, 2,   'FizzBuzz12'],
            'inclusive endpoints' => [7, 8,   '78'],
        ];
    }

    #[DataProvider('rangeProvider')]
    public function testRangeConcatenation(int $start, int $stop, string $expected): void
    {
        $this->assertSame($expected, fizzBuzz($start, $stop));
    }

    /** @return array<string, array{int, int}> */
    public static function invalidRangeProvider(): array
    {
        return [
            'stop less than start' => [10, 1],
            'start negative'       => [-1, 10],
            'both negative'        => [-5, -1],
        ];
    }

    #[DataProvider('invalidRangeProvider')]
    public function testThrowsOnInvalidRange(int $start, int $stop): void
    {
        $this->expectException(InvalidArgumentException::class);
        fizzBuzz($start, $stop);
    }
}
