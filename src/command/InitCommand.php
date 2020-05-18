<?php
/**
 * Author: Artem Aleksandrov
 * Date: 16.05.2020
 * Time: 12:30
 */

namespace kradwhite\language\command;


use kradwhite\language\LangException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package kradwhite\language\command
 */
class InitCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'init';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Создание структуры катологов или создание таблицы под хранение текстов')
            ->setHelp('Создаёт структуру катологов или создаёт таблицу под хранение текстов');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed|void
     * @throws LangException
     */
    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        if ($app = $this->buildApp($input, $output)) {
            $app->config()->init();
        }
        return (int)!$app;
    }
}