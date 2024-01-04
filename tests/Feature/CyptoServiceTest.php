<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Services\CryptoService;

final class Tests extends TestCase
{
    private $cryptoService;

    protected function setUp(): void
    {
        $this->cryptoService = new CryptoService();
    }

    public function testEncrypt(): void
    {
        $message = (string) "Hello, world!";
        $secretCode = (string) "secret";

        $encrypted = $this->cryptoService->encrypt($message, $secretCode);

        $this->assertIsString($encrypted);
        $this->assertNotEquals($message, $encrypted);
    }

    public function testDecrypt(): void
    {
        $message = "Hello, world!";
        $secretCode = "secret";

        $encrypted = $this->cryptoService->encrypt((string)$message, (string)$secretCode);
        
        $cryptoService = new CryptoService;
        $decrypted = $cryptoService->decrypt((string)$encrypted, (string)$secretCode);

        $this->assertEquals($message, $decrypted);
    }

    public function testDecryptWithWrongSecretCode(): void
    {
        $message = (string) "Hello, world!";
        $secretCode = (string) "secret";
        $wrongSecretCode = (string) 'wrongSecret';

        $encrypted = $this->cryptoService->encrypt($message, $secretCode);
        $decrypted = $this->cryptoService->decrypt($encrypted, $wrongSecretCode);

        $this->assertStringContainsString('Error:', $decrypted);
    }
}