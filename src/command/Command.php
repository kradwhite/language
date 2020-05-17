<?php
/**
 * Date: 12.04.2020
 * Time: 14:51
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\command;

use kradwhite\db\exception\DbException;
use kradwhite\db\exception\PdoException;
use kradwhite\language\Config;
use kradwhite\language\LangException;
use kradwhite\language\Lang;
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
     * @return App|null
     * @throws MigrationException
     */
    protected function buildApp(InputInterface $input, OutputInterface $output): ?App
    {
        if (!$this->app) {
            if (!$target = $this->getConfigFileName($input)) {
                $output->writeln('<fg=red>Ошика получения рабочего каталога. Возмножно нехватает доступа на чтение у одно из каталогов в цепочке.</>');
            }
            $this->app = $target ? new App($target) : null;
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
        return $path . Config::Name;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws LangException
     * @throws PdoException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->doExecute($input, $output);
            $output->writeln("<fg=green>Успешно выполнено</>");
        } catch (LangException|DbException $e) {
            $output->writeln("<fg=red>{$e->getMessage()}</>");
        }
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addOption('path', 'p', InputOption::VALUE_OPTIONAL,
            'Путь хранения файла конфигурации языка');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    abstract protected function doExecute(InputInterface $input, OutputInterface $output);
}