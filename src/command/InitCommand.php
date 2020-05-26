<?php
/**
 * Author: Artem Aleksandrov
 * Date: 16.05.2020
 * Time: 12:30
 */

namespace kradwhite\language\command;


use kradwhite\db\exception\DbException;
use kradwhite\language\LangException;
use kradwhite\language\LocalLang;
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
        $this->setDescription(LocalLang::init()->phrase('messages', 'language-init'))
            ->setHelp(LocalLang::init()->phrase('messages', 'language-init2'));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws LangException
     * @throws DbException
     */
    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        if ($app = $this->buildApp($input, $output)) {
            $app->createTexts();
        }
        return (int)!$app;
    }
}