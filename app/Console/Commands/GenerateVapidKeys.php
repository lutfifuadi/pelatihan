<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class GenerateVapidKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:generate-vapid-keys
                            {--force : Timpa VAPID keys yang sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate VAPID public/private keys untuk Web Push notification';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $envPath = base_path('.env');

        if (! File::exists($envPath)) {
            $this->error('File .env tidak ditemukan. Silakan copy .env.example terlebih dahulu.');

            return self::FAILURE;
        }

        $envContent = File::get($envPath);

        $hasPublicKey = str_contains($envContent, 'VAPID_PUBLIC_KEY=');
        $hasPrivateKey = str_contains($envContent, 'VAPID_PRIVATE_KEY=');

        if (($hasPublicKey || $hasPrivateKey) && ! $this->option('force')) {
            $this->warn('VAPID keys sudah ada di .env.');
            $this->warn('Gunakan --force untuk generate ulang dan menimpa keys lama.');

            return self::FAILURE;
        }

        $this->info('Generating VAPID keys via web-push CLI...');

        $keys = $this->generateKeys();

        if ($keys === null) {
            $this->error('Gagal generate VAPID keys. Pastikan Node.js dan npx tersedia.');
            $this->line('Alternatif manual: npx web-push generate-vapid-keys');

            return self::FAILURE;
        }

        $this->info('Public Key:');
        $this->line($keys['publicKey']);
        $this->info('Private Key:');
        $this->line($keys['privateKey']);

        $this->updateEnv($envPath, $envContent, [
            'VAPID_PUBLIC_KEY' => $keys['publicKey'],
            'VAPID_PRIVATE_KEY' => $keys['privateKey'],
            'VAPID_SUBJECT' => 'mailto:admin@pelatihanku.com',
        ]);

        $this->info('VAPID keys berhasil disimpan ke .env');
        $this->warn('Pastikan .env tidak di-commit ke repository (sudah ada di .gitignore).');

        return self::SUCCESS;
    }

    /**
     * Generate VAPID keys menggunakan web-push CLI (Node.js).
     *
     * @return array{publicKey: string, privateKey: string}|null
     */
    private function generateKeys(): ?array
    {
        $process = new Process(['npx', 'web-push', 'generate-vapid-keys'], base_path());
        $process->setTimeout(120);
        $process->run();

        if (! $process->isSuccessful()) {
            return null;
        }

        $output = $process->getOutput();

        preg_match('/Public Key:\s*(\S+)/', $output, $publicMatch);
        preg_match('/Private Key:\s*(\S+)/', $output, $privateMatch);

        if (empty($publicMatch[1]) || empty($privateMatch[1])) {
            return null;
        }

        return [
            'publicKey' => trim($publicMatch[1]),
            'privateKey' => trim($privateMatch[1]),
        ];
    }

    /**
     * Update atau tambahkan variabel environment ke file .env.
     *
     * @param  array<string, string>  $values
     */
    private function updateEnv(string $envPath, string $envContent, array $values): void
    {
        foreach ($values as $key => $value) {
            $pattern = '/^'.preg_quote($key, '/').'=.*/m';
            $line = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $line, $envContent);
            } else {
                $envContent .= "\n{$line}";
            }
        }

        File::put($envPath, $envContent);
    }
}
