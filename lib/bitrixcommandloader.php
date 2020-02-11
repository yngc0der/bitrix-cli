<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

namespace Yngc0der\Cli;

use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Command\Command;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

/**
 * Class BitrixCommandLoader
 * @package Yngc0der\Cli
 */
class BitrixCommandLoader implements CommandLoaderInterface
{
    const ON_LOAD_EVENT_NAME = 'OnCommandsLoad';

    /** @var array */
    protected $commands;

    /**
     * BitrixCommandLoader constructor.
     */
    public function __construct()
    {
        $this->commands = [];
        $this->loadSystemCommands();
        $this->loadCommands();
    }

    /**
     * @return void
     */
    protected function loadCommands()
    {
        $event = new Event('yngc0der.cli', static::ON_LOAD_EVENT_NAME);
        $event->send();

        /** @var EventResult $eventResult */
        foreach($event->getResults() as $eventResult) {
            if ($eventResult->getType() == EventResult::ERROR) {
                continue;
            }

            if (!is_array($eventResult->getParameters())) {
                continue;
            }

            /** @var Command $parameter */
            foreach ($eventResult->getParameters() as $parameter) {
                if (!$parameter instanceof Command) {
                    continue;
                }

                $this->add($parameter);
            }
        }
    }

    /**
     * @return void
     */
    public function loadSystemCommands()
    {
        $this->add(new \Bitrix\Main\Cli\OrmAnnotateCommand());

        try {
            if (\Bitrix\Main\ModuleManager::isModuleInstalled('translate')
                && \Bitrix\Main\Loader::includeModule('translate')
            ) {
                $this->add(new \Bitrix\Translate\Cli\IndexCommand());
            }
        } catch (\Bitrix\Main\LoaderException $e) {}
    }

    /**
     * @inheritDoc
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new CommandNotFoundException(sprintf('Command "%s" does not exist.', $name));
        }

        return $this->commands[$name];
    }

    /**
     * @inheritDoc
     */
    public function has($name)
    {
        return key_exists($name, $this->commands);
    }

    /**
     * @inheritDoc
     */
    public function getNames()
    {
        return array_keys($this->commands);
    }

    /**
     * @param Command $command
     * @return bool
     */
    protected function add(Command $command)
    {
        $this->commands[$command->getName()] = $command;
        return true;
    }
}
