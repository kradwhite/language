<?php
/**
 * Author: Artem Aleksandrov
 * Date: 16.05.2020
 * Time: 11:22
 */

namespace kradwhite\language\command;

use kradwhite\language\LangException;
use kradwhite\language\LocalLang;
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
        $this->setDescription(LocalLang::init()->phrase('messages', 'config-create'))
            ->setHelp(LocalLang::init()->phrase('messages', 'config-create2'));
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
            $app->initConfig($input->getOption('path'));
        }
        return (int)!$app;
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    protected function getConfigFileName(InputInterface $input): string
    {
        return __DIR__ . '/../config/templates/language.php';
    }
}