<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InstallerController extends Controller
{
    private function logInstaller($message, $level = 'info')
    {
        $logPath = storage_path('logs/installer.log');
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        if ($level === 'error') {
            \Illuminate\Support\Facades\Log::error("Installer: " . $message);
        } else {
            \Illuminate\Support\Facades\Log::info("Installer: " . $message);
        }
    }

    public function step1()
    {
        $this->logInstaller('Memulai instalasi Step 1: Pengecekan Requirement');

        $this->setEnv([]);

        $requirements = [
            'PHP Version >= 8.1' => version_compare(phpversion(), '8.1.0', '>='),
            'Database Driver (PDO/MySQL)' => extension_loaded('pdo') && extension_loaded('pdo_mysql'),
            'OpenSSL & JSON' => extension_loaded('openssl') && extension_loaded('json'),
            'XML & Curl' => extension_loaded('xml') && extension_loaded('curl'),
            'Storage & Cache Writable' => is_writable(storage_path()) && is_writable(base_path('bootstrap/cache')),
        ];

        $allPassed = !in_array(false, $requirements);

        return view('installer.step1', compact('requirements', 'allPassed'));
    }

    public function step2()
    {
        return view('installer.step2');
    }

    public function step2Submit(Request $request)
    {
        $this->logInstaller('Memproses Step 2: Konfigurasi Database');

        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
        ]);

        session([
            'install_db_host' => $request->db_host,
            'install_db_port' => $request->db_port,
            'install_db_name' => $request->db_name,
            'install_db_user' => $request->db_user,
            'install_db_pass' => $request->db_password ?? '',
            'install_confirm_wipe' => $request->has('confirm_wipe'),
        ]);

        // Deteksi apakah database sudah berisi tabel
        try {
            $pdo = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_user,
                $request->db_password ?? '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_TIMEOUT => 5]
            );
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            if (count($tables) > 0 && !$request->has('confirm_wipe')) {
                $this->logInstaller('Database tidak kosong! Terdeteksi ' . count($tables) . ' tabel.');
                return redirect()->route('installer.step2')
                    ->with('db_warning', true)
                    ->with('db_table_count', count($tables));
            }
        } catch (\Exception $e) {
            // Abaikan, koneksi sudah dicek di testConnection
        }

        return redirect()->route('installer.step3');
    }

    public function testConnection(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
        ]);

        try {
            $pdo = new \PDO(
                "mysql:host={$request->db_host};port={$request->db_port};dbname={$request->db_name}",
                $request->db_user,
                $request->db_password ?? '',
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_TIMEOUT => 5]
            );

            // Cek apakah ada tabel existing
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
            $warning = count($tables) > 0
                ? ' Database memiliki ' . count($tables) . ' tabel existing.'
                : '';

            return response()->json([
                'success' => true,
                'message' => 'Koneksi berhasil!' . $warning,
                'tables_count' => count($tables),
                'has_tables' => count($tables) > 0,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Koneksi gagal: ' . $e->getMessage()]);
        }
    }

    public function step3()
    {
        return view('installer.step3');
    }

    public function progress()
    {
        $progressFile = storage_path('framework/install-progress.json');
        if (file_exists($progressFile)) {
            $current = json_decode(file_get_contents($progressFile), true) ?: ['step' => 0, 'label' => 'Memulai...'];
        } else {
            $current = ['step' => 0, 'label' => 'Memulai...'];
        }
        return response()->json($current);
    }

    private function setProgress($step, $label)
    {
        $progressFile = storage_path('framework/install-progress.json');
        $data = json_encode(['step' => $step, 'label' => $label, 'updated_at' => time()]);
        file_put_contents($progressFile, $data, LOCK_EX);
    }

    public function process(Request $request)
    {
        $this->logInstaller('Memulai Proses Akhir Instalasi');

        $request->validate([
            'app_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email',
            'admin_password' => 'required|min:6',
        ]);

        set_time_limit(0);
        ignore_user_abort(true);

        try {
            $this->setProgress(1, 'Menulis konfigurasi database...');
            $this->logInstaller('Menulis konfigurasi ke .env');

            $this->setEnv([
                'DB_HOST' => session('install_db_host', '127.0.0.1'),
                'DB_PORT' => session('install_db_port', '3306'),
                'DB_DATABASE' => session('install_db_name', 'laravel'),
                'DB_USERNAME' => session('install_db_user', 'root'),
                'DB_PASSWORD' => session('install_db_pass', ''),
                'APP_NAME' => $request->app_name,
                'DB_CONNECTION' => 'mysql',
            ]);

            Artisan::call('config:clear');

            $this->setProgress(2, 'Migrasi database...');
            $this->logInstaller('Menjalankan migrasi database');

            // Jika user konfirmasi hapus data lama, pakai migrate:fresh
            if (session('install_confirm_wipe')) {
                $this->logInstaller('Mode: migrate:fresh (hapus data lama)');
                Artisan::call('migrate:fresh', ['--force' => true, '--seed' => false]);
            } else {
                Artisan::call('migrate', ['--force' => true]);
            }

            $this->setProgress(3, 'Seeder data awal...');
            $this->logInstaller('Menjalankan database seed');
            Artisan::call('db:seed', ['--force' => true]);

            $this->setProgress(4, 'Mengoptimasi pengaturan...');
            // Update .env: alihkan session/cache/queue ke database
            $this->setEnv([
                'SESSION_DRIVER' => 'database',
                'CACHE_STORE' => 'database',
                'QUEUE_CONNECTION' => 'database',
            ]);

            $this->setProgress(5, 'Membuat akun admin...');
            $this->logInstaller('Membuat user admin');

            // Cek dulu apakah admin sudah ada (dari seeder) untuk hindari duplicate email
            $admin = User::where('email', $request->admin_email)->where('role', 'admin')->first();

            if ($admin) {
                // Update password jika admin dari seeder ingin diganti passwordnya
                $admin->update([
                    'name' => $request->admin_name,
                    'password' => Hash::make($request->admin_password),
                ]);
                $this->logInstaller('Admin sudah ada, password diperbarui');
            } else {
                User::create([
                    'name' => $request->admin_name,
                    'email' => $request->admin_email,
                    'password' => Hash::make($request->admin_password),
                    'role' => 'admin',
                ]);
                $this->logInstaller('User admin baru berhasil dibuat');
            }

            $this->setProgress(6, 'Storage link & finalisasi...');
            $this->logInstaller('Membuat storage link');
            if (!file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }

            file_put_contents(storage_path('installed'), 'installed on ' . date('Y-m-d H:i:s'));

            $this->setProgress(7, 'Optimasi cache...');
            $this->logInstaller('Optimizing cache');
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            // Hapus file progress
            $progressFile = storage_path('framework/install-progress.json');
            if (file_exists($progressFile)) {
                @unlink($progressFile);
            }

            session()->forget([
                'install_db_host', 'install_db_port', 'install_db_name',
                'install_db_user', 'install_db_pass', 'install_progress',
                'install_confirm_wipe',
            ]);

            $this->logInstaller('Instalasi selesai dengan sukses');

            return response()->json([
                'success' => true,
                'message' => 'Instalasi Berhasil!',
                'redirect' => url('/login'),
            ]);
        } catch (\Exception $e) {
            $this->setProgress(0, 'Gagal: ' . $e->getMessage());
            $this->logInstaller('Error: ' . $e->getMessage(), 'error');
            \Illuminate\Support\Facades\Log::error('Installer Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Hapus file progress biar ga nyangkut
            $progressFile = storage_path('framework/install-progress.json');
            if (file_exists($progressFile)) {
                @unlink($progressFile);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses instalasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function setEnv($data = [])
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            $examplePath = base_path('.env.example');
            if (file_exists($examplePath)) {
                copy($examplePath, $path);
            } else {
                file_put_contents($path, '');
            }
        }

        $env = file_get_contents($path);

        if (!isset($data['APP_KEY']) || empty($data['APP_KEY'])) {
            if (preg_match('/^APP_KEY=(.*)$/m', $env, $matches) && !empty($matches[1])) {
                $data['APP_KEY'] = trim($matches[1]);
            } else {
                $data['APP_KEY'] = 'base64:' . base64_encode(random_bytes(32));
            }
        }

        foreach ($data as $key => $value) {
            $envValue = (preg_match('/[\s"\'#]/', (string) $value))
                ? '"' . addcslashes((string) $value, '"\\') . '"'
                : (string) $value;

            $pattern = "/^#?\s*{$key}=.*$/m";

            if (preg_match($pattern, $env)) {
                $env = preg_replace_callback(
                    $pattern,
                    fn() => "{$key}={$envValue}",
                    $env
                );
            } else {
                $env .= "\n{$key}={$envValue}";
            }
        }

        try {
            if (!file_put_contents($path, $env)) {
                throw new \Exception('Gagal menulis ke file .env');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Installer setEnv Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
