<?php

namespace Machus\LaravelArtisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the application (clear all caches and run fresh migrations with seeding)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Resetting application...');
        $this->newLine();

        // Run clear:all command
        $this->comment('Step 1: Clearing all caches...');
        Artisan::call('clear:all', [], $this->getOutput());
        $this->newLine();

        // Run migrate:fresh with seed and force
        $this->comment('Step 2: Running fresh migrations with seeding...');
        
        try {
            Artisan::call('migrate:fresh', [
                '--seed' => true,
                '--force' => true,
            ], $this->getOutput());
            
            $this->newLine();
            $this->info('Application reset successfully!');
            
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('Failed to reset application: ' . $e->getMessage());
            
            return self::FAILURE;
        }
    }
}
