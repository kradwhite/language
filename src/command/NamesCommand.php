<?php
/**
 * Date: 20.05.2020
 * Time: 20:30
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\command;

use kradwhite\language\LangException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NamesCommand
 * @package kradwhite\language\command
 */
class NamesCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'names';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Вывод имён текстов со всех конфигураций')
            ->setHelp('Выводит имёна текстов со всех конфигураций');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws LangException
     */
    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        if ($app = $this->buildApp($input, $output)) {
            foreach ($app->names() as $name) {
                $output->writeln($name);
            }
        }
        return (int)!$app;
    }
}