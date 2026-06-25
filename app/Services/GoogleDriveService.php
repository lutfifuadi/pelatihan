<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use App\Models\GoogleDriveSetting;
use Exception;

class GoogleDriveService
{
    protected $client;
    protected $service;

    public function __construct($accessToken = null)
    {
        $config = GoogleDriveConfigService::getConfig();

        $this->client = new Client();
        $this->client->setClientId($config['client_id']);
        $this->client->setClientSecret($config['client_secret']);
        $this->client->setRedirectUri($config['redirect_uri']);
        $this->client->addScope(Drive::DRIVE);
        
        if ($accessToken) {
            $this->client->setAccessToken($accessToken);
        } else {
            $setting = GoogleDriveSetting::first();
            if ($setting && $setting->google_access_token) {
                $token = json_decode($setting->google_access_token, true);
                $this->client->setAccessToken($token);
                
                if ($this->client->isAccessTokenExpired()) {
                    if ($setting->google_refresh_token) {
                        $this->client->fetchAccessTokenWithRefreshToken($setting->google_refresh_token);
                        $newToken = $this->client->getAccessToken();
                        $setting->update([
                            'google_access_token' => json_encode($newToken),
                        ]);
                    }
                }
            }
        }
        
        $this->service = new Drive($this->client);
    }

    public function uploadFile(string $fileContent, string $fileName, string $folderId = null): ?array
    {
        try {
            $mimeType = 'image/jpeg';

            // Cari file dengan nama dan mimeType yang sama di folder target
            $fileId = null;
            if ($folderId) {
                $query = "'{$folderId}' in parents and name = '{$fileName}' and trashed = false and mimeType = '{$mimeType}'";
                $existingFiles = $this->service->files->listFiles([
                    'q' => $query,
                    'fields' => 'files(id)',
                    'supportsAllDrives' => true,
                    'includeItemsFromAllDrives' => true,
                ]);

                $files = $existingFiles->getFiles();
                if (count($files) > 0) {
                    $fileId = $files[0]->getId();
                }
            }

            $fileMetadata = new Drive\DriveFile([
                'name' => $fileName,
            ]);

            if ($fileId) {
                // Update file yang sudah ada
                $file = $this->service->files->update($fileId, $fileMetadata, [
                    'data' => $fileContent,
                    'mimeType' => $mimeType,
                    'uploadType' => 'multipart',
                    'fields' => 'id,webViewLink',
                    'supportsAllDrives' => true,
                ]);
            } else {
                if ($folderId) {
                    $fileMetadata->setParents([$folderId]);
                }

                $file = $this->service->files->create($fileMetadata, [
                    'data' => $fileContent,
                    'mimeType' => $mimeType,
                    'uploadType' => 'multipart',
                    'fields' => 'id,webViewLink',
                    'supportsAllDrives' => true,
                ]);
            }

            return [
                'id' => $file->id,
                'url' => $file->webViewLink,
            ];
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    public function findFolderByName(string $name, string $parentId): ?string
    {
        try {
            $query = "mimeType='application/vnd.google-apps.folder' and name='" . str_replace("'", "\\'", $name) . "'";
            if ($parentId) {
                $query .= " and '$parentId' in parents";
            }
            
            $results = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name)',
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true,
            ]);
            
            $files = $results->getFiles();
            return count($files) > 0 ? $files[0]->getId() : null;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    public function createFolder(string $name, string $parentId): ?string
    {
        try {
            $fileMetadata = new Drive\DriveFile([
                'name' => $name,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentId],
            ]);
            
            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id',
                'supportsAllDrives' => true,
            ]);
            
            return $folder->id;
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    public function ensureFolderExists(string $name, string $parentId): ?string
    {
        $existing = $this->findFolderByName($name, $parentId);
        if ($existing) {
            return $existing;
        }
        return $this->createFolder($name, $parentId);
    }
}
