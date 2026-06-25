<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    /**
     * Resize and store the image.
     *
     * @param UploadedFile $file
     * @param int $width
     * @param int $height
     * @return string The path to the stored image.
     */
    public function resizeAndStore(UploadedFile $file, int $width = 500, int $height = 500): string
    {
        // Create an image manager instance with a driver
        $manager = new ImageManager(new Driver());

        // Read the image from the uploaded file
        $image = $manager->read($file->getRealPath());

        // Resize the image
        $image->cover($width, $height);

        // Encode the image to JPG format with 80% quality
        $encodedImage = $image->toJpeg(80);

        // Define a unique name for the file
        $fileName = uniqid() . '_' . time() . '.jpg';

        // Define the storage path
        $path = 'public/resized_images/' . $fileName;

        // Store the image
        Storage::put($path, (string) $encodedImage);

        // Return the public URL to the stored image
        return Storage::url($path);
    }

    /**
     * Resize image and return binary string (for direct upload).
     */
    public function resizeToBinary(UploadedFile $file, int $width = 500, int $height = 500): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getRealPath());
        $image->cover($width, $height);
        return (string) $image->toJpeg(80);
    }

    /**
     * Resize from base64 and return binary string.
     */
    public function resizeBase64ToBinary(string $base64Data, int $width = 500, int $height = 500): string
    {
        $imageData = base64_decode($base64Data);
        $tempPath = tempnam(sys_get_temp_dir(), 'foto_');
        file_put_contents($tempPath, $imageData);

        $file = new \Illuminate\Http\UploadedFile(
            $tempPath,
            'foto.jpg',
            'image/jpeg',
            null,
            true
        );

        $result = $this->resizeToBinary($file, $width, $height);

        @unlink($tempPath);

        return $result;
    }
}
