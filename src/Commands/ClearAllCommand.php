<?php

namespace Machus\LaravelArtisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearAllCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all clear commands (cache, config, route, view, compiled)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Clearing all caches...');

        $commands = [
            'cache:clear' => 'Application cache',
            'config:clear' => 'Configuration cache',
            'route:clear' => 'Route cache',
            'view:clear' => 'Compiled views',
            'clear-compiled' => 'Compiled class file',
        ];

        foreach ($commands as $command => $description) {
            $this->comment("Clearing {$description}...");
            
            try {
                Artisan::call($command);
                $this->line("  ✓ {$description} cleared");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to clear {$description}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('All caches cleared successfully!');

        return self::SUCCESS;
    }
}
