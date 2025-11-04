<?php

namespace Machus\LaravelArtisan\Commands;

use Illuminate\Console\Command;

class UserDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete 
                            {identifier : The user ID or email}
                            {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a user by ID or email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->checkAuthSetup()) {
            return self::FAILURE;
        }

        $userModel = $this->getUserModel();
        $identifier = $this->argument('identifier');

        // Find user by ID or email
        $user = is_numeric($identifier)
            ? $userModel::find($identifier)
            : $userModel::where('email', $identifier)->first();

        if (!$user) {
            $this->error("User not found: {$identifier}");
            return self::FAILURE;
        }

        // Show user info
        $this->table(
            ['ID', 'Name', 'Email', 'Created At'],
            [[$user->id, $user->name, $user->email, $user->created_at]]
        );

        // Confirm deletion
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to delete this user?')) {
                $this->info('User deletion cancelled.');
                return self::SUCCESS;
            }
        }

        // Delete user
        try {
            $user->delete();
            $this->info("User deleted successfully!");
            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to delete user: {$e->getMessage()}");
            return self::FAILURE;
        }
    }

    /**
     * Check if Laravel auth is properly set up.
     */
    protected function checkAuthSetup(): bool
    {
        $userModel = $this->getUserModel();

        if (!class_exists($userModel)) {
            $this->error("User model not found: {$userModel}");
            $this->error('Laravel authentication framework is not installed.');
            $this->line('Run: php artisan make:auth or install Laravel Breeze/Jetstream/Fortify');
            return false;
        }

        return true;
    }

    /**
     * Get the User model class.
     */
    protected function getUserModel(): string
    {
        return config('auth.providers.users.model', 'App\\Models\\User');
    }
}
