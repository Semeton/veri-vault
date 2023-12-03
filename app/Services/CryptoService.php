<?php

declare(strict_types=1);

namespace App\Services;

class CryptoService
{
    private $key;

    public function __construct()
    {
        // $this->key = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        $this->key = "SECRET";
    }

    public function encrypt(string $message, string $secretCode): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        // $nonce = "SECRET";

        $cipher = base64_encode(
            $nonce.
            sodium_crypto_secretbox(
                $message,
                $nonce,
                hash('sha256', $this->key . $secretCode, true)
            )
        );

        sodium_memzero($message);
        sodium_memzero($this->key);

        return $cipher;
    }

    public function decrypt(string $encrypted, string $secretCode): string
    {
        try {
            $decoded = base64_decode($encrypted);
            $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
            $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

            try {
                $plain = sodium_crypto_secretbox_open(
                    $ciphertext,
                    $nonce,
                    hash('sha256', $this->key . $secretCode, true)
                );
            } catch (\Exception $e) {
                return new \Exception("Decryption failed: " . $e->getMessage());
            }


            if (!is_string($plain)) {
                return new \Exception("Invalid cipher text.");
            }

            sodium_memzero($ciphertext);
            sodium_memzero($this->key);

            return $plain;
        } catch (\Exception $e) {
            throw new \Exception("Invalid cipher text: " . $e->getMessage());
        }
    }
}