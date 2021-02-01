<?php

declare(strict_types=1);

namespace GarbuzIvan\TelegramBot;

use GarbuzIvan\TelegramBot\Commands\AbstractCommand;

class Configuration
{
    /**
     * @var string
     */
    protected string $configFile = 'gi-telegram-bot';

    /**
     * The array of commands class.
     *
     * @var array
     */
    protected array $commands = [];

    /**
     * Create new token if api token exists in user
     *
     * @var ?string
     */
    protected ?string $token = null;

    /**
     * Configuration constructor.
     * @param Configuration|null $config
     */
    public function __construct(Configuration $config = null)
    {
        if (is_null($config)) {
            $this->load();
        }
    }

    /**
     * @return $this|Configuration
     */
    public function load(): Configuration
    {
        $pipes = config($this->configFile . '.pipes');
        if(is_array($pipes)){
            $this->setPipes($pipes);
        }
        return $this;
    }

    /**
     * @param array $commands
     */
    public function setCommands(array $commands): void
    {
        $this->commands = [];
        foreach ($commands as $command) {
            if (get_parent_class($command) == AbstractCommand::class) {
                $this->commands[] = $command;
            }
        }
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

}
