<?php
/**
 * Date: 12.05.2020
 * Time: 20:23
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\db\exception\BeforeQueryException;
use kradwhite\language\Config;
use kradwhite\language\LangException;

/**
 * Class TextFactory
 * @package kradwhite\language
 */
class TextFactory
{
    /**
     * @param array $config
     * @param string $locale
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws BeforeQueryException
     */
    public function buildText(array $config, string $locale, string $name): Text
    {
        if (in_array($config['type'], ['php'])) {
            return $this->buildFileText($config, $locale, $name);
        } else if ($config['type'] == 'database') {
            return $this->buildDbText($config, $locale, $name);
        } else {
            throw new LangException("Неизвестный тип '{$config['type']}' ресурсов");
        }
    }

    /**
     * @param Config $config
     * @return Texts
     */
    public function buildTexts(Config $config): Texts
    {
        return new Texts($config);
    }

    /**
     * @param array $config
     * @param array $locales
     * @return void
     * @throws LangException
     */
    public function initTexts(array $config, array $locales)
    {
        if (in_array($config['type'], ['php'])) {
            $this->initFileTexts($config, $locales);
        } else if ($config['type'] == 'database') {
            $this->initDbTexts($config, $locales);
        } else {
            throw new LangException("Неизвестный тип '{$config['type']}' ресурсов");
        }
    }

    /**
     * @param array $config
     * @param string $locale
     * @param string $name
     * @return FileText
     * @throws LangException
     */
    private function buildFileText(array $config, string $locale, string $name): FileText
    {
        if (!isset($config['directory'])) {
            throw new LangException("Для ресурсов типа '{$config['type']}' требуется имя директории 'directory' => 'path'");
        }
        $filename = $config['directory'] . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . "$name.{$config['type']}";
        if (!file_exists($filename)) {
            throw new LangException("Файл '$filename' не существует");
        }
        $texts = $this->getFileContent($filename, $config['type']);
        return new FileText($locale, $name, $texts);
    }

    /**
     * @param string $filename
     * @param string $extension
     * @return array
     * @throws LangException
     */
    private function getFileContent(string $filename, string $extension): array
    {
        if ($extension == 'php') {
            return require $filename;
        } else {
            throw new LangException("Неизвестный тип '$extension' файла '$filename'");
        }
    }

    /**
     * @param array $config
     * @param string $locale
     * @param string $name
     * @return DbText
     * @throws LangException
     */
    private function buildDbText(array &$config, string $locale, string $name): DbText
    {
        return new DbText($locale, $name, $this->buildTextRepository($config));
    }

    /**
     * @param array $config
     * @param array $locales
     * @return void
     * @throws LangException
     */
    private function initFileTexts(array $config, array $locales)
    {
        if (!isset($config['directory'])) {
            throw new LangException("Для ресурсов типа '{$config['type']}' требуется имя директории 'directory' => 'path'");
        } else if (!file_exists($config['directory']) && !mkdir($config['directory'], 0775)) {
            throw new LangException("Ошибка создания директории '{$config['directory']}'");
        }
        foreach ($locales as $locale) {
            $localePath = $config['directory'] . DIRECTORY_SEPARATOR . $locale;
            if (!file_exists($locale)) {
                if (!mkdir($localePath, 0775)) {
                    throw new LangException("Ошибка создания директории '$localePath'");
                }
            }
            foreach ($config['names'] as $name) {
                $filename = $localePath . DIRECTORY_SEPARATOR . "$name.php";
                if (!file_exists($filename)) {
                    if (!touch($filename)) {
                        throw new LangException("Ошибка создания файла '$filename'");
                    }
                    chmod($filename, 0664);
                }
            }
        }
    }

    /**
     * @param array $config
     * @param array $locales
     * @return void
     * @throws LangException
     */
    private function initDbTexts(array $config, array $locales)
    {
        $this->buildTextRepository($config)->createTable($config['table'], $config['columns'], $locales);
    }

    /**
     * @param array $config
     * @return TextRepository
     * @throws LangException
     */
    private function buildTextRepository(array &$config): TextRepository
    {
        if (!isset($config['connection'])) {
            throw new LangException("Для ресурсов типа '{$config['type']}' требуется конфигурация 'connection' => []");
        }
        if (!isset($config['repository'])) {
            $config['repository'] = SqlTextRepository::class;
        } else if (!is_a($config['repository'], TextRepository::class, true)) {
            throw new LangException("Класс репозитория '{$config['repository']}' должен реализовывать интерфейс '" . TextRepository::class . "'");
        }
        if ($config['type'] == 'database') {
            return new $config['repository']($config);
        } else {
            throw new LangException("Неизвестный тип '{$config['type']}' базы данных");
        }
    }
}