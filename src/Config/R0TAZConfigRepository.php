<?php
namespace Rotaz\EventProcessor\Config;

/**
 * Repository for managing event processor configurations.
 */
class R0TAZConfigRepository
{
    /**
     * @var array<string, EventProcessorConfig>
     */
    protected array $configs;


    /**
     * @param EventProcessorConfig $eventProcessorConfig
     * @return void
     */
    public function addConfig(EventProcessorConfig $eventProcessorConfig): void
    {
        $this->configs[$eventProcessorConfig->name] = $eventProcessorConfig;
    }

    /**
     * Retrieves the configuration for a given name.
     *
     * @param string $name The name of the configuration to retrieve.
     * @return EventProcessorConfig|null The configuration object if found, or null if it does not exist.
     */
    public function getConfig(string $name): ?EventProcessorConfig
    {
        return $this->configs[$name] ?? null;
    }
}
