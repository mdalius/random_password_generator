<?php
declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RandomStringTest extends TestCase
{
    /** @return iterable<string, array{int}> */
    public static function lengths(): iterable
    {
        foreach ([1, 6, 7, 8, 9, 18, 32, 256] as $length) {
            yield "length $length" => [$length];
        }
    }

    #[DataProvider('lengths')]
    public function testDefaultKeyspaceAndLength(int $length): void
    {
        $password = random_str($length);

        self::assertSame($length, strlen($password));
        self::assertMatchesRegularExpression('/\A[0-9a-zA-Z]+\z/', $password);
    }

    public function testCustomKeyspace(): void
    {
        $password = random_str(128, '01');

        self::assertSame(128, strlen($password));
        self::assertMatchesRegularExpression('/\A[01]+\z/', $password);
    }

    /** @return iterable<string, array{int}> */
    public static function invalidLengths(): iterable
    {
        yield 'zero' => [0];
        yield 'negative' => [-1];
    }

    #[DataProvider('invalidLengths')]
    public function testRejectsNonPositiveLength(int $length): void
    {
        $this->expectException(InvalidArgumentException::class);
        random_str($length);
    }

    /** @return iterable<string, array{string}> */
    public static function invalidKeyspaces(): iterable
    {
        yield 'empty' => [''];
        yield 'single byte' => ['a'];
    }

    #[DataProvider('invalidKeyspaces')]
    public function testRejectsShortKeyspace(string $keyspace): void
    {
        $this->expectException(InvalidArgumentException::class);
        random_str(16, $keyspace);
    }
}
