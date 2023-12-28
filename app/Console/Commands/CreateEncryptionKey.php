<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * @author Semeton Balogun <balogunsemeton@gmail.com>
 */

class CreateEncryptionKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:encryption-key';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (empty(env('ENCRYPTION_KEY'))) {
            $key = bin2hex(random_bytes(32));
            file_put_contents(base_path('.env'), PHP_EOL . "ENCRYPTION_KEY={$key}", FILE_APPEND);
            $this->info('Encryption key has been set.');
        } else {
            $this->info('Encryption key already exists.');
        }
    }
}