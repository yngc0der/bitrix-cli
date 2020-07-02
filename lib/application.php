<?php
/**
 * @author RG. <rg.archuser@gmail.com>
 */

namespace Yngc0der\Cli;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Loader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application
 * @package Yngc0der\Cli
 */
class Application extends \Symfony\Component\Console\Application
{
    const VERSION = '1.1.0';
    const NAME = 'yngc0der.cli';
    const ON_LOAD_EVENT_NAME = 'OnCommandsLoad';

    /**
     * @inheritDoc
     */
    public function __construct($name = self::NAME, $version = self::VERSION)
    {
        parent::__construct($name, $version);
    }

    /**
     * @inheritDoc
     * @throws \Bitrix\Main\LoaderException
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();

        $commands[] = new \Bitrix\Main\Cli\OrmAnnotateCommand();

        if (Loader::includeModule('translate')) {
            $commands[] = new \Bitrix\Translate\Cli\IndexCommand();
        }

        return $commands;
    }

    /**
     * @return void
     */
    private function doLoadCommands()
    {
        $event = new Event(self::NAME, self::ON_LOAD_EVENT_NAME);
        $event->send();

        foreach($event->getResults() as $eventResult) {
            if (
                $eventResult->getType() === EventResult::ERROR
                || !is_array($eventResult->getParameters())
            ) {
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

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->doLoadCommands();

        return parent::doRun($input, $output);
    }
}
