<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\HTTPResponseEnum;

class CryptoService
{
    private string $key;

    public function __construct()
    {
        $this->key = env("ENCRYPTION_KEY");
    }

    /**
     * Encrypt a string
     *
     * @param string $message
     * @param string $secretCode
     * @return string
     */
    public function encrypt(string $message, string $secretCode): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $cipher = base64_encode(
            $nonce .
                sodium_crypto_secretbox(
                    $message,
                    $nonce,
                    hash("sha256", $this->key . $secretCode, true)
                )
        );

        sodium_memzero($message);
        sodium_memzero($this->key);

        return $cipher;
    }

    /**
     * Decrypt an encrypted string
     *
     * @param string $encrypted
     * @param string $secretCode
     * @return string
     */
    public function decrypt(string $encrypted, string $secretCode): string
    {
        try {
            $decoded = base64_decode($encrypted);
            $nonce = mb_substr(
                $decoded,
                0,
                SODIUM_CRYPTO_SECRETBOX_NONCEBYTES,
                "8bit"
            );
            $ciphertext = mb_substr(
                $decoded,
                SODIUM_CRYPTO_SECRETBOX_NONCEBYTES,
                null,
                "8bit"
            );

            try {
                $plain = sodium_crypto_secretbox_open(
                    $ciphertext,
                    $nonce,
                    hash("sha256", $this->key . $secretCode, true)
                );
            } catch (\Exception $e) {
                abort(
                    HTTPResponseEnum::BAD_REQUEST,
                    "Error: Decryption failed: " . $e->getMessage()
                );
                // return "Error: Decryption failed: " . $e->getMessage();
            }

            if (!is_string($plain)) {
                abort(
                    HTTPResponseEnum::BAD_REQUEST,
                    "Decryption failed: invalid secret code"
                );
                // return "Error: Invalid data or secret code";
            }

            sodium_memzero($ciphertext);
            sodium_memzero($this->key);

            return $plain;
        } catch (\Exception $e) {
            abort(
                HTTPResponseEnum::BAD_REQUEST,
                "Decryption failed: secret code"
            );
            // return "Error: Invalid cipher text: " . $e->getMessage();
        }
    }
}
