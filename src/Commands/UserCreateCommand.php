<?php

namespace Machus\LaravelArtisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
                            {--name= : The name of the user}
                            {--email= : The email of the user}
                            {--password= : The password of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->checkAuthSetup()) {
            return self::FAILURE;
        }

        $userModel = $this->getUserModel();

        // Get user input
        $name = $this->option('name') ?: $this->ask('Name');
        $email = $this->option('email') ?: $this->ask('Email');
        $password = $this->option('password') ?: $this->secret('Password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . (new $userModel)->getTable()],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        // Create user
        try {
            $user = $userModel::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $this->info("User created successfully!");
            $this->table(
                ['ID', 'Name', 'Email', 'Created At'],
                [[$user->id, $user->name, $user->email, $user->created_at]]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Failed to create user: {$e->getMessage()}");
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
