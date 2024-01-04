<?php

declare(strict_types=1);

namespace Semeton\CryptoService;

/**
 * Class CryptoService
 * @author Semeton Balogun <balogunsemeton@gmail.com> 2024
 * 
 * This class provides methods for encryption and decryption of messages.
 * It uses Sodium cryptographic library for encryption and decryption, ensuring high-level security.
 *
 * @package App\Services
 */
class CryptoService
{
    /**
     * The key used for encryption and decryption.
     *
     * @var string
     */
    private string $key;

    /**
     * CryptoService constructor.
     *
     * Initializes the key with a predefined string.
     */
    public function __construct()
    {
        $this->key = 'a80070d3253ccc0c5b9b7e235ae6783a5a6474175399582fd51003bab6250139'; //Define a random string
    }

    /**
     * Encrypts a message with a secret code.
     *
     * @param string $message The message to encrypt.
     * @param string $secretCode The secret code to use for encryption.
     * @return string The encrypted message.
     */
    public function encrypt(string $message, string $secretCode): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

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

    /**
     * Decrypts an encrypted message with a secret code.
     *
     * @param string $encrypted The encrypted message to decrypt.
     * @param string $secretCode The secret code to use for decryption.
     * @return string The decrypted message or an error message if decryption fails.
     */
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
                return "Error: Decryption failed: " . $e->getMessage();
            }

            sodium_memzero($ciphertext);
            sodium_memzero($this->key);

            return $plain;
        } catch (\Exception $e) {
            return "Error: Invalid cipher text: " . $e->getMessage();
        }
    }
}