<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Semeton\CryptoService\CryptoService;

final class Tests extends TestCase
{
    private $cryptoService;

    protected function setUp(): void
    {
        $this->cryptoService = new CryptoService();
    }

    public function testEncrypt(): void
    {
        echo "Running testEncrypt...\n";
        $message = (string) "Hello, world!";
        $secretCode = (string) "secret";

        $encrypted = $this->cryptoService->encrypt($message, $secretCode);

        $this->assertIsString($encrypted);
        echo "Asserts that the encrypted message is a string.\n";
        $this->assertNotEquals($message, $encrypted);
        echo "Asserts that plain message is not equal to the encrypted message\n";
    }

    public function testDecrypt(): void
    {
        echo "Running testDecrypt...\n";
        $message = "Hello, world!";
        $secretCode = "secret";

        $encrypted = $this->cryptoService->encrypt((string)$message, (string)$secretCode);
        
        $cryptoService = new CryptoService;
        $decrypted = $cryptoService->decrypt((string)$encrypted, (string)$secretCode);

        $this->assertEquals($message, $decrypted);
        echo "Asserts that plain message is equal to the decrypted message\n";
    }

    public function testDecryptWithWrongSecretCode(): void
    {
        echo "Running testDecryptWithWrongSecretCode...\n";
        $message = (string) "Hello, world!";
        $secretCode = (string) "secret";
        $wrongSecretCode = (string) 'wrongSecret';

        $encrypted = $this->cryptoService->encrypt($message, $secretCode);
        $decrypted = $this->cryptoService->decrypt($encrypted, $wrongSecretCode);

        $this->assertStringContainsString('Error:', $decrypted);
        echo "Asserts that string contains an error\n";
    }
}