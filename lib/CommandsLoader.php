<?php

namespace Yngc0der\Cli;

use Bitrix\Main\Loader;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Command\Command;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

final class CommandsLoader implements CommandLoaderInterface
{
    public const ON_LOAD_EVENT_NAME = 'OnCommandsLoad';

    /** @var Command[] */
    protected $commands;

    public function __construct()
    {
        $this->commands = [];
        $this->loadSystemCommands();
        $this->loadCommandsFromEventHandlers();
    }

    public function get($name): Command
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        return $this->commands[$name];
    }

    public function has($name): bool
    {
        return key_exists($name, $this->commands);
    }

    public function getNames(): array
    {
        return array_keys($this->commands);
    }

    private function loadCommandsFromEventHandlers(): void
    {
        $event = new Event('yngc0der.cli', self::ON_LOAD_EVENT_NAME);
        $event->send();

        foreach ($event->getResults() as $eventResult) {
            if ($eventResult->getType() === EventResult::ERROR) {
                continue;
            }

            if (!is_array($eventResult->getParameters())) {
                continue;
            }

            foreach ($eventResult->getParameters() as $parameter) {
                if (!$parameter instanceof Command) {
                    continue;
                }

                $this->add($parameter);
            }
        }
    }

    private function loadSystemCommands(): void
    {
        $this->add(new \Bitrix\Main\Cli\OrmAnnotateCommand());

        if (Loader::includeModule('translate')) {
            $this->add(new \Bitrix\Translate\Cli\IndexCommand());
        }
    }

    private function add(Command $command): void
    {
        $this->commands[$command->getName()] = $command;
    }
}
