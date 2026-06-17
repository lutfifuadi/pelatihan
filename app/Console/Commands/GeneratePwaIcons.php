<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePwaIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:generate-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate PWA icon files (PNG) for all required sizes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating PWA icons...');

        $sizes = [72, 96, 128, 144, 152, 192, 384, 512];
        $iconsDir = public_path('icons');

        if (!is_dir($iconsDir)) {
            mkdir($iconsDir, 0755, true);
        }

        // Colors matching the SVG theme
        $primaryColor = [115, 103, 240];  // #7367f0
        $darkColor = [26, 29, 41];         // #1a1d29

        foreach ($sizes as $size) {
            $this->line("  Generating {$size}x{$size} icon...");

            // Create a truecolor image
            $image = imagecreatetruecolor($size, $size);
            imagesavealpha($image, true);

            // Enable anti-aliasing
            imageantialias($image, true);

            // Transparent background
            $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
            imagefill($image, 0, 0, $transparent);

            // Draw rounded rectangle background (with gradient effect approximation)
            $bgColor = imagecolorallocate($image, $primaryColor[0], $primaryColor[1], $primaryColor[2]);
            $this->drawRoundedRect($image, 0, 0, $size - 1, $size - 1, $size * 0.15, $bgColor);

            // Draw the letter "P" in white
            $fontSize = max(5, intval($size * 0.55));
            $white = imagecolorallocate($image, 255, 255, 255);

            // Use a built-in font (number from 1-5 for built-in)
            $font = 5; // largest built-in font
            if ($fontSize > 20) {
                $font = 5;
            } elseif ($fontSize > 15) {
                $font = 4;
            } elseif ($fontSize > 10) {
                $font = 3;
            } else {
                $font = 2;
            }

            // Get text width and height for centering
            $text = 'P';
            $textWidth = imagefontwidth($font) * strlen($text);
            $textHeight = imagefontheight($font);
            $x = ($size - $textWidth) / 2;
            $y = ($size - $textHeight) / 2;

            // Add shadow for depth
            $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 40);
            imagestring($image, $font, $x + 1, $y + 1, $text, $shadowColor);
            imagestring($image, $font, $x, $y, $text, $white);

            $filePath = $iconsDir . "/icon-{$size}x{$size}.png";
            imagepng($image, $filePath);
            imagedestroy($image);

            $this->info("    ✓ Created: icons/icon-{$size}x{$size}.png");
        }

        // Also generate a badge icon (96x96) for notifications
        $badgeSize = 96;
        $badge = imagecreatetruecolor($badgeSize, $badgeSize);
        imagesavealpha($badge, true);
        $transparent = imagecolorallocatealpha($badge, 0, 0, 0, 127);
        imagefill($badge, 0, 0, $transparent);
        $bgColor = imagecolorallocate($badge, $primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $this->drawRoundedRect($badge, 0, 0, $badgeSize - 1, $badgeSize - 1, $badgeSize * 0.15, $bgColor);
        $white = imagecolorallocate($badge, 255, 255, 255);
        $font = 5;
        $text = 'P';
        $textWidth = imagefontwidth($font) * strlen($text);
        $textHeight = imagefontheight($font);
        $x = ($badgeSize - $textWidth) / 2;
        $y = ($badgeSize - $textHeight) / 2;
        imagestring($badge, $font, $x, $y, $text, $white);
        imagepng($badge, $iconsDir . '/badge-72x72.png');
        imagedestroy($badge);
        $this->info("    ✓ Created: icons/badge-72x72.png");

        $this->newLine();
        $this->info('✅ All PWA icons generated successfully!');
        $this->line('Location: ' . $iconsDir);

        return Command::SUCCESS;
    }

    /**
     * Draw a rounded rectangle with anti-aliasing.
     */
    private function drawRoundedRect($image, $x1, $y1, $x2, $y2, $radius, $color)
    {
        $radius = min($radius, ($x2 - $x1) / 2, ($y2 - $y1) / 2);
        $radius = max(0, $radius);

        // Draw four corners as circles
        imagefilledellipse($image, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);
        imagefilledellipse($image, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, $color);

        // Draw rectangles to fill the gaps
        imagefilledrectangle($image, $x1 + $radius, $y1, $x2 - $radius, $y2, $color);
        imagefilledrectangle($image, $x1, $y1 + $radius, $x2, $y2 - $radius, $color);
    }
}
