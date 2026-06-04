<?php

namespace App\Console\Commands;

use App\Models\Retailer;
use App\Services\ScoringService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('snap:score')]
#[Description('Calculate and store risk scores for all SNAP retailers')]
class ScoreRetailers extends Command
{
    public function handle(ScoringService $scoringService): int
    {
        $total = Retailer::count();

        $this->info("Scoring {$total} retailers...");

        $scoringService->scoreAll();

        $this->info('Done.');

        return Command::SUCCESS;
    }
}
