<?php

namespace Gordiichuk\FieldModeration;

use Illuminate\Support\ServiceProvider;

class FieldModerationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
                __DIR__.'/../config/field-moderation.php',
                'field-moderation'
        );
    }

    public function boot(): void
    {
        $this->publishes([
                __DIR__.'/../config/field-moderation.php' => config_path('field-moderation.php'),
        ], 'field-moderation-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
