<?php
/**
 * Date: 12.05.2020
 * Time: 20:23
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

use kradwhite\db\exception\BeforeQueryException;

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
        } else if (in_array($config['type'], ['sql'])) {
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
     * @throws BeforeQueryException
     */
    private function buildDbText(array &$config, string $locale, string $name): DbText
    {
        if (!isset($config['connection'])) {
            throw new LangException("Для ресурсов типа '{$config['type']}' требуется конфигурация 'connection' => []");
        }
        if ($config['type'] == 'sql') {
            $repository = new SqlTextRepository($config);
        } else {
            throw new LangException("Неизвестный тип '{$config['type']}' базы данных");
        }
        return new DbText($locale, $name, $repository);
    }
}