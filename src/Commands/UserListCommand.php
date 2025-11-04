<?php

namespace Machus\LaravelArtisan\Commands;

use Illuminate\Console\Command;

class UserListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list 
                            {--limit=50 : Number of users to display}
                            {--search= : Search by name or email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->checkAuthSetup()) {
            return self::FAILURE;
        }

        $userModel = $this->getUserModel();
        $limit = (int) $this->option('limit');
        $search = $this->option('search');

        try {
            // Build query
            $query = $userModel::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->latest()->limit($limit)->get();

            if ($users->isEmpty()) {
                $this->info('No users found.');
                return self::SUCCESS;
            }

            // Display users
            $this->info("Showing {$users->count()} user(s):");
            $this->newLine();

            $this->table(
                ['ID', 'Name', 'Email', 'Created At'],
                $users->map(fn($user) => [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->created_at->format('Y-m-d H:i:s'),
                ])->toArray()
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to list users: {$e->getMessage()}");
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
