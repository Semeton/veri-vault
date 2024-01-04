# CryptoService

CryptoService is a package that provides a simple yet secure way to encrypt and decrypt messages using a secret code. It leverages the power of the Sodium cryptographic library to ensure the highest level of security. This document provides a comprehensive guide on how to install and use CryptoService, along with a detailed explanation of its methods.

## Installation

Installing CryptoService is a breeze with the package manager [composer](https://getcomposer.org/). Follow the link to download and install composer if you have not already.

## CryptoService Class Documentation

### Overview

The `CryptoService` class provides encryption and decryption services using the Sodium cryptographic library. It uses a secret key of a 64 bits of random string.

### Methods

#### `__construct()`

This is the constructor method for the `CryptoService` class.

- Parameters: None
- Returns: None
- Description: Initializes the `$key` property with the value of the `ENCRYPTION_KEY` environment variable.

#### `encrypt(string $message, string $secretCode): string`

This method encrypts a given message.

- Parameters:
  - `$message` (string): The message to be encrypted.
  - `$secretCode` (string): A secret code used in the encryption process.
- Returns: The encrypted message as a base64-encoded string.
- Description: This method generates a random nonce and uses it along with the `$message` and a hashed combination of `$key` and `$secretCode` to create an encrypted cipher. The cipher is then base64-encoded for safe transmission or storage. The memory of the `$message` and `$key` is cleared after encryption for security reasons.

#### `decrypt(string $encrypted, string $secretCode): string`

This method decrypts a given encrypted message.

- Parameters:
  - `$encrypted` (string): The message to be decrypted.
  - `$secretCode` (string): A secret code used in the decryption process.
- Returns: The decrypted message as a string. If decryption fails, it returns an error message.
- Description: This method decodes the `$encrypted` message, extracts the nonce and ciphertext, and attempts to decrypt the ciphertext using the nonce and a hashed combination of `$key` and `$secretCode`. If decryption is successful and the result is a string, it returns the decrypted message. If decryption fails or the result is not a string, it returns an error message. The memory of the ciphertext and `$key` is cleared after decryption for security reasons.
