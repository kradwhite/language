<?php
/**
 * Author: Artem Aleksandrov
 * Date: 16.05.2020
 * Time: 11:22
 */

namespace kradwhite\language\command;

use kradwhite\config\ConfigException;
use kradwhite\language\Lang;
use kradwhite\language\LangException;
use kradwhite\language\LocalLang;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $text = LocalLang::init()->text('messages');
        $this->setDescription($text->phrase('config-create'))
            ->setHelp($text->phrase('config-create2'))
            ->addOption('locale', 'l', InputOption::VALUE_OPTIONAL,
                $text->phrase('locale'), 'en');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws LangException
     * @throws ConfigException
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
     * @param OutputInterface $output
     * @return Lang|null
     * @throws LangException
     */
    protected function buildApp(InputInterface $input, OutputInterface $output): ?Lang
    {
        $config = require __DIR__ . '/../config/templates/language.php';
        return new Lang($config, $input->getOption('locale'));
    }
}