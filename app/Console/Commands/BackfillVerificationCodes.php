<?php

namespace App\Console\Commands;

use App\Models\Enrollment;
use App\Services\VerificationCodeService;
use Illuminate\Console\Command;

class BackfillVerificationCodes extends Command
{
    protected $signature = 'enrollments:backfill-verification-codes 
                            {--dry-run : Only show count, don\'t update}
                            {--limit=0 : Max records to process (0 = all)}';

    protected $description = 'Backfill verification codes for existing approved enrollments';

    public function handle(): int
    {
        $query = Enrollment::where('status', 'approved')
            ->whereNull('verification_code');

        $total = $query->count();

        if ($total === 0) {
            $this->info('No approved enrollments need backfill.');
            return Command::SUCCESS;
        }

        $this->warn("Found {$total} approved enrollments without verification codes.");

        if ($this->option('dry-run')) {
            $this->info('Dry-run mode. No changes made.');
            return Command::SUCCESS;
        }

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $enrollments = $query->get();
        $bar = $this->output->createProgressBar($enrollments->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($enrollments as $enrollment) {
            try {
                $enrollment->update([
                    'verification_code' => VerificationCodeService::generate($enrollment),
                    'verification_code_expires_at' => now()->addHours(24),
                    'status' => 'approved',
                ]);
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("Enrollment #{$enrollment->id}: {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done! {$success} updated, {$failed} failed.");

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
