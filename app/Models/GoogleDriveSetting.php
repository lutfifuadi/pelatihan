<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleDriveSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_client_id',
        'google_client_secret',
        'google_redirect_uri',
        'google_root_folder_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'is_connected',
        'updated_by',
    ];

    protected $casts = [
        'is_connected' => 'boolean',
        'google_token_expires_at' => 'datetime',
        'google_client_secret' => 'encrypted',
        'google_access_token' => 'encrypted',
        'google_refresh_token' => 'encrypted',
    ];
}
