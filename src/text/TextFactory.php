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
    public function buildFileText(string $locale, string $name, array $texts): FileText
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
            throw new LangException('texts-not-found');
        }
        $result = [];
        foreach ($config['texts'] as $textConfig) {
            if (!isset($textConfig['names'])) {
                throw new LangException('names-not-found');
            } else if (in_array($textConfig['type'], ['php'])) {
                $result[] = $this->buildFileTexts($textConfig);
            } else if ($textConfig['type'] == 'database') {
                $result[] = $this->buildDbTexts($textConfig);
            } else {
                throw new LangException('type-unknown', [$textConfig['type']]);
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
            throw new LangException('dir-key-not-found', [$config['type']]);
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
            throw new LangException('repo-wrong', [$config['repository']]);
        }
        $repository = new $config['repository']($dbConfig);
        return new DbTexts($config['type'], $config['names'], $this, $dbConfig, $repository);
    }
}