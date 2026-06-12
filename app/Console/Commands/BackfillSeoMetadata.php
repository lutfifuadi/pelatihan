<?php

namespace App\Console\Commands;

use App\Models\Pelatihan;
use Illuminate\Console\Command;

class BackfillSeoMetadata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:backfill {model? : Model class to backfill (e.g. "Pelatihan")}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill SEO metadata untuk record existing yang belum punya SeoMetadata';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $models = $this->getModels();

        foreach ($models as $modelClass) {
            $shortName = class_basename($modelClass);
            $this->info("Memproses {$shortName}...");

            $count = 0;
            $modelClass::whereDoesntHave('seo')->chunk(100, function ($records) use (&$count) {
                foreach ($records as $record) {
                    $defaults = $record->initializeSeo();
                    $record->seo()->create($defaults);
                    $count++;
                }
            });

            $this->info("  ✅ {$count} record {$shortName} berhasil di-backfill.");
        }

        $this->newLine();
        $this->info('🎉 Backfill SEO metadata selesai!');
    }

    private function getModels(): array
    {
        $specific = $this->argument('model');

        if ($specific) {
            $class = 'App\\Models\\' . $specific;
            if (!class_exists($class)) {
                $this->error("Model {$class} tidak ditemukan.");
                return [];
            }
            return [$class];
        }

        return [
            Pelatihan::class,
        ];
    }
}
