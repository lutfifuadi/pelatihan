<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Exception;

class GoogleSheetsService
{
    protected $client;
    protected $service;

    public function __construct()
    {
        $this->client = new Client();
        // NOTE: Path to credentials file needs to be configured
        $this->client->setAuthConfig(storage_path('app/google_credentials.json'));
        $this->client->addScope(Sheets::SPREADSHEETS_READONLY);
        $this->service = new Sheets($this->client);
    }

    /**
     * Read data from a Google Sheet.
     *
     * @param string $spreadsheetId
     * @param string $range
     * @return array|null
     */
    public function readSheet(string $spreadsheetId, string $range): ?array
    {
        try {
            $response = $this->service->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } catch (Exception $e) {
            // Log the error
            report($e);
            return null;
        }
    }
}
