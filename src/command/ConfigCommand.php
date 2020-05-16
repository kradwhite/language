<?php
/**
 * Author: Artem Aleksandrov
 * Date: 16.05.2020
 * Time: 11:22
 */

namespace kradwhite\language\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigCommand
 * @package kradwhite\language
 */
class ConfigCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'config';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Создание файла конфигурации языка')
            ->setHelp('Создаёт файл конфигурации языка');
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {

    }
}