<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$users = App\Models\User::select('id','name','nik','role','is_active','email')->get();
foreach($users as $u) {
    echo "ID:{$u->id} | Nama:{$u->name} | NIK:{$u->nik} | Role:{$u->role} | Active:{$u->is_active} | Email:{$u->email}\n";
}
echo 'Total: ' . $users->count() . ' users\n';
