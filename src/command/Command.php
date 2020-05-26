<?php
/**
 * Date: 12.04.2020
 * Time: 14:51
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\command;

use kradwhite\db\exception\DbException;
use kradwhite\language\Lang;
use kradwhite\language\LangException;
use kradwhite\language\LocalLang;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 * @package command
 */
abstract class Command extends \Symfony\Component\Console\Command\Command
{
    /** @var Lang */
    private ?Lang $app = null;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return Lang|null
     * @throws LangException
     */
    protected function buildApp(InputInterface $input, OutputInterface $output): ?Lang
    {
        if (!$this->app) {
            if (!$target = $this->getConfigFileName($input)) {
                $output->writeln(LocalLang::init()->phrase('messages', 'get-directory-error'));
            } else if (!file_exists($target)) {
                $output->writeln(LocalLang::init()->phrase('messages', 'file-not-exist', [$target]));
            } else {
                $this->app = $target ? new Lang(require_once $target) : null;
            }
        }
        return $this->app;
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    protected function getConfigFileName(InputInterface $input): string
    {
        if (!$path = $input->getOption('path') ?? getcwd()) {
            return '';
        } else if ($path[0] != DIRECTORY_SEPARATOR) {
            if (!$prefix = getcwd()) {
                return '';
            }
            $path = $prefix . DIRECTORY_SEPARATOR . $path;
        }
        if ($path[strlen($path) - 1] != DIRECTORY_SEPARATOR) {
            $path .= DIRECTORY_SEPARATOR;
        }
        return $path . Lang::Name;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws LangException
     * @throws DbException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if (!$result = $this->doExecute($input, $output)) {
                $output->writeln(LocalLang::init()->phrase('messages', 'success'));
            }
            return $result;
        } catch (LangException|DbException $e) {
            $output->writeln(LocalLang::init()->phrase('messages', 'exception', [$e->getMessage()]));
        }
        return 1;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption('path', 'p', InputOption::VALUE_OPTIONAL,
            LocalLang::init()->phrase('messages', 'filepath'), '.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    abstract protected function doExecute(InputInterface $input, OutputInterface $output);
}