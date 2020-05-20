<?php
/**
 * Date: 12.05.2020
 * Time: 20:23
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\language\LangException;

/**
 * Class TextFactory
 * @package kradwhite\language
 */
class TextFactory
{
    /**
     * @param string $locale
     * @param string $name
     * @param array $texts
     * @return FileText
     */
    public function buildFileText(string $locale, string $name, array &$texts): FileText
    {
        return new FileText($locale, $name, $texts);
    }

    /**
     * @param string $locale
     * @param string $name
     * @param TextRepository $repository
     * @return DbText
     */
    public function buildDbText(string $locale, string $name, TextRepository $repository): DbText
    {
        return new DbText($locale, $name, $repository);
    }

    /**
     * @param array $config
     * @return Texts[]
     * @throws LangException
     */
    public function buildTexts(array $config): array
    {
        if (!isset($config['texts'])) {
            throw new LangException("Конфигурация языков должна содержать массив 'texts' => []");
        }
        $result = [];
        foreach ($config['texts'] as $textConfig) {
            if (!isset($textConfig['names'])) {
                throw new LangException("Конфигурация языка должна содержать массив имён текстов 'names' => []");
            } else if (in_array($config['type'], ['php'])) {
                $result[] = $this->buildFileTexts($config);
            } else if ($config['type'] == 'database') {
                $result[] = $this->buildDbTexts($config);
            } else {
                throw new LangException("Неизвестный тип '{$config['type']}' ресурсов");
            }
        }
        return $result;
    }

    /**
     * @param array $config
     * @return FileTexts
     * @throws LangException
     */
    public function buildFileTexts(array $config): FileTexts
    {
        if (!isset($config['directory'])) {
            throw new LangException("Для ресурсов типа '{$config['type']}' требуется имя директории 'directory' => 'path'");
        }
        return new FileTexts($config['type'], $config['names'], $this, $config['directory']);
    }

    /**
     * @param array $config
     * @return DbTexts
     * @throws LangException
     */
    public function buildDbTexts(array $config): DbTexts
    {
        $dbConfig = new DbConfig($config);
        if (!isset($config['repository'])) {
            $config['repository'] = SqlTextRepository::class;
        } else if (!is_a($config['repository'], TextRepository::class, true)) {
            throw new LangException("Класс репозитория '{$config['repository']}' должен реализовывать интерфейс '" . TextRepository::class . "'");
        }
        $repository = new $config['repository']($dbConfig);
        return new DbTexts($config['type'], $config['names'], $this, $dbConfig, $repository);
    }
}