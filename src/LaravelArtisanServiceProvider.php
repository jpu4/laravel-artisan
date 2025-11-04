<?php

namespace Machus\LaravelArtisan;

use Machus\LaravelArtisan\Commands\ClearAllCommand;
use Machus\LaravelArtisan\Commands\ResetCommand;
use Machus\LaravelArtisan\Commands\UserCreateCommand;
use Machus\LaravelArtisan\Commands\UserDeleteCommand;
use Machus\LaravelArtisan\Commands\UserListCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelArtisanServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-artisan')
            ->hasCommands([
                ClearAllCommand::class,
                ResetCommand::class,
                UserCreateCommand::class,
                UserDeleteCommand::class,
                UserListCommand::class,
            ]);
    }
}
