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

        $document = new DOMDocument();
        $document->loadHTML($html);
        $fields = $document->getElementsByTagName('textarea');
        self::assertCount(6, $fields);
        $lengths = [6, 7, 8, 9, 18, 32];
        foreach ($fields as $index => $field) {
            self::assertTrue($field->hasAttribute('readonly'));
            self::assertSame('off', $field->getAttribute('wrap'));
            self::assertSame('passwords-' . $lengths[$index], $field->getAttribute('id'));
            $passwords = explode("\n", $field->textContent);
            self::assertCount(12, $passwords);
            foreach ($passwords as $password) {
                self::assertSame($lengths[$index], strlen($password));
                self::assertMatchesRegularExpression('/\A[0-9a-zA-Z]+\z/', $password);
            }
        }
    }
}
