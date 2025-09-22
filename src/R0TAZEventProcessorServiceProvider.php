<?php

namespace Rotaz\EventProcessor;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Rotaz\EventProcessor\Config\EventProcessorConfig;
use Rotaz\EventProcessor\Config\R0TAZConfigRepository;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Rotaz\EventProcessor\Exceptions\InvalidConfig;
use Rotaz\EventProcessor\Exceptions\InvalidMethod;

/**
 * Service provider for the R0TAZ Event Processor package.
 *
 * This provider is responsible for configuring the package, registering
 * framework-specific macros, and bootstrapping necessary bindings for the
 * application's service container.
 */
class R0TAZEventProcessorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('event-processor')
            ->hasConfigFile()
            ->hasMigrations('create_inbound_data_table');
    }

    public function packageRegistered()
    {
        Route::macro('rotaz', function (string $url, string $name = 'default', $method = 'post') {
            if (! in_array($method, ['get', 'post', 'put', 'patch', 'delete'])) {
                throw InvalidMethod::make($method);
            }

            if (config('event-processor.add_unique_route_name', false)) {
                $name .= '.' . Str::random(8);
            }

            return Route::{$method}($url, '\Rotaz\EventProcessor\Services\Port\Http\HttpController')
                ->name("rotaz-{$name}");
        });
    }

    public function packageBooted()
    {
        $this->app->scoped(R0TAZConfigRepository::class, function () {
            $configRepository = new R0TAZConfigRepository();

            collect(config('event-processor.configs'))
                ->map(fn (array $config) => new EventProcessorConfig($config))
                ->each(fn (EventProcessorConfig $eventProcessorConfig) => $configRepository->addConfig($eventProcessorConfig));

            return $configRepository;
        });

        $this->app->scoped(R0TAZConfigRepository::class, function () {
            $configRepository = new R0TAZConfigRepository();

            collect(config('event-processor.configs'))
                ->map(fn (array $config) => new EventProcessorConfig($config))
                ->each(fn (EventProcessorConfig $eventProcessorConfig) => $configRepository->addConfig($eventProcessorConfig));

            return $configRepository;
        });

        $this->app->bind(EventProcessorConfig::class, function () {
            $routeName = Route::currentRouteName() ?? '';

            $configName = Str::after($routeName, 'rotaz-');

            if (config('event-processor.add_unique_token_to_route_name', false)) {
                $routeNameSuffix = Str::after($routeName, 'rotaz-');

                $configName = Str::before($routeNameSuffix, '.');
            }

            $eventProcessorConfig = app(R0TAZConfigRepository::class)->getConfig($configName);

            if (is_null($eventProcessorConfig)) {
                throw InvalidConfig::couldNotFindConfig($configName);
            }

            return $eventProcessorConfig;
        });
    }
}
