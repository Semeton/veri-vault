# VeriVault App

## Overview

The SecureMessaging app is a privacy-focused application that enables users to encrypt and decrypt messages using a zero-knowledge-proof mechanism built on top of sodium hashing. Users can create a special secret code that serves as the key for encryption and decryption processes.

## API Documentation

Link to the [API documentation](https://documenter.getpostman.com/view/19842116/2s9YsGhD6t)

## Features

-   **Message Encryption/Decryption:** Encrypt and decrypt messages securely using user-generated secret codes.
-   **Zero-Knowledge-Proof:** Utilizes sodium hashing mechanisms to ensure a zero-knowledge-proof architecture.
-   **Access Permissions:** Grant different levels of access permissions for encrypted or decrypted messages.

## How it Works

1. **User Authentication:** Users authenticate themselves and create a special secret code.
2. **Encryption:** Messages are encrypted using the user's secret code, ensuring privacy and security.
3. **Decryption:** Authorized users can decrypt messages using their secret code.
4. **Access Permissions:** Users can grant different access permissions to others for encrypted or decrypted messages.

## Technologies Used

-   Laravel for backend development.
-   Sodium hashing for zero-knowledge-proof.
-   Livewire for dynamic and interactive user interfaces.
-   Tailwind for responsive and clean UI.

## Installation

1. Clone the repository: `git clone https://github.com/Semeton/veri_vault.git`
2. Install dependencies: `composer install && npm install`
3. Creat the env file: `cp .env.example .env`
4. Set up the application key: `php artisan key:generate`
5. Set up the encryption key: `php artisan create:encryption-key`
6. Set up the database: `php artisan migrate`
7. Run the development server: `php artisan serve`

## Contribution Guidelines

Contributions are welcome! Please follow the [contribution guidelines](CONTRIBUTING.md) when contributing to this project.

## License

This project is licensed under the [MIT License](LICENSE).
