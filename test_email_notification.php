<?php

$user = App\Models\User::find(1);
echo "User: {$user->name}, Email: {$user->email}" . PHP_EOL;

$service = app(App\Services\NotificationService::class);
$result = $service->sendByTemplate($user, 'welcome_peserta', [
    'nama' => $user->name,
    'pelatihan' => 'Pelatihan Web Developer',
], 'email');

if ($result) {
    echo "Notification created: ID={$result->id}, Channel={$result->channel}, Status={$result->status}, Recipient={$result->recipient}" . PHP_EOL;
} else {
    echo "Notification failed to create." . PHP_EOL;
}
