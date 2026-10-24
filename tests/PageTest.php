<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PageTest extends TestCase
{
    public function testPageContainsPasswordsMatchingColumnLengths(): void
    {
        ob_start();
        try {
            require __DIR__ . '/../index.php';
            $html = (string) ob_get_contents();
        } finally {
            ob_end_clean();
        }

        preg_match_all('/<td>([^<]*)<\/td>/', $html, $matches);
        self::assertCount(72, $matches[1]);

        $lengths = [6, 7, 8, 9, 18, 32];
        foreach ($matches[1] as $index => $password) {
            self::assertSame($lengths[$index % count($lengths)], strlen($password));
            self::assertMatchesRegularExpression('/\A[0-9a-zA-Z]+\z/', $password);
        }
    }
}
