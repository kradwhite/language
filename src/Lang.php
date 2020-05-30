<?php
/**
 * Date: 11.05.2020
 * Time: 20:32
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

use kradwhite\config\Config;
use kradwhite\config\ConfigException;
use kradwhite\db\exception\DbException;
use kradwhite\language\text\Text;
use kradwhite\language\text\TextFactory;
use kradwhite\language\text\Texts;

/**
 * Class Lang
 * @package kradwhite\language
 */
class Lang
{
    /** @var string */
    const Name = 'language.php';

    /** @var string */
    private string $locale;

    /** @var Texts[] */
    private array $texts = [];

    /** @var array */
    private array $config;

    /**
     * Lang constructor.
     * @param array $config
     * @param string $locale
     * @throws LangException
     */
    public function __construct(array $config, string $locale = 'ru')
    {
        $this->config = $config;
        if (!isset($this->config['locales'])) {
            $this->config['locales'] = [$locale ? $locale : 'ru'];
        }
        if ($locale && !in_array($locale, $this->config['locales'])) {
            $this->config['locales'][] = $locale;
        }
        if (!isset($this->config['default'])) {
            $this->config['default'] = 'default';
        }
        $this->locale = $locale && in_array($locale, $this->config['locales']) ? $locale : $this->config['locales'][0];
        $this->initTexts();
    }

    /**
     * @param string $configFilename
     * @param string $locale
     * @return Lang
     * @throws LangException
     */
    public static function init(string $configFilename, string $locale = 'ru'): Lang
    {
        if (!file_exists($configFilename)) {
            throw new LangException("config-file-not-found", [$configFilename]);
        }
        return new Lang(require $configFilename, $locale);
    }

    /**
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws DbException
     */
    public function text(string $name = ''): Text
    {
        return $this->getTextByName($name);
    }

    /**
     * @param string $name
     * @param string $id
     * @param array $params
     * @return string
     * @throws LangException
     * @throws DbException
     */
    public function phrase(string $name, string $id, array $params = []): string
    {
        return $this->getTextByName($name)->phrase($id, $params);
    }

    /**
     * @return string
     */
    public function locale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $path
     * @return void
     * @throws ConfigException
     * @throws LangException
     */
    public function initConfig(string $path)
    {
        $source = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src/config';
        if (!isset($path[0]) || $path[0] != DIRECTORY_SEPARATOR) {
            if ($path && $path[strlen($path) - 1] != DIRECTORY_SEPARATOR) {
                $path .= DIRECTORY_SEPARATOR;
            }
            $path = getcwd() . DIRECTORY_SEPARATOR . $path;
        }
        if (file_exists($path . DIRECTORY_SEPARATOR . self::Name)) {
            throw new LangException('config-file-already-exist', [$path . DIRECTORY_SEPARATOR . self::Name]);
        }
        (new Config($source))->build($path, $this->locale);
    }

    /**
     * @return void
     * @throws DbException
     * @throws LangException
     */
    public function createTexts()
    {
        foreach ($this->texts as $texts) {
            $texts->create($this->config['locales']);
        }
    }

    /**
     * @return array
     */
    public function names(): array
    {
        $result = [];
        foreach ($this->texts as $texts) {
            $result = array_merge($result, $texts->getNames());
        }
        return $result;
    }

    /**
     * @param string $name
     * @return Text
     * @throws LangException
     * @throws DbException
     */
    private function getTextByName(string $name): Text
    {
        $name = $name ? $name : $this->config['default'];
        foreach ($this->texts as $texts) {
            if ($texts->existName($name)) {
                return $texts->getText($this->locale, $name);
            }
        }
        throw new LangException('text-not-found', [$name]);
    }

    /**
     * @return void
     * @throws LangException
     */
    private function initTexts()
    {
        if (!isset($this->config['factory'])) {
            $this->config['factory'] = TextFactory::class;
        } else if (!is_a($this->config['factory'], TextFactory::class, true)) {
            throw new LangException('factory-wrong', [$this->config['factory']]);
        }
        $factory = new $this->config['factory']();
        $this->texts = $factory->buildTexts($this->config);
    }
}