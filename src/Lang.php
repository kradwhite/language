<?php
/**
 * Date: 11.05.2020
 * Time: 20:32
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

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
            $this->config['locales'] = ['ru'];
        }
        if (!isset($this->config['default'])) {
            $this->config['default'] = 'default';
        }
        $this->locale = $locale ? $locale : $this->config['locales'][0];
        $this->initTexts();
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
     * @throws LangException
     */
    public function initConfig(string $path)
    {
        $source = __DIR__ . DIRECTORY_SEPARATOR . self::Name;
        $target = $path . DIRECTORY_SEPARATOR . self::Name;
        if (!file_exists($path)) {
            throw new LangException("Директория '$path' не существует");
        } else if (!is_dir($path)) {
            throw new LangException("'$path' не является директорией");
        } else if (file_exists($target)) {
            throw new LangException("Файл конфигурации '$target' уже существует");
        } else if (!file_exists($source)) {
            throw new LangException("Исходный файл конфигурации языков не найден '$source'");
        } else if (!copy($source, $target)) {
            throw new LangException("Ошибка копирования файла конфигурации '$target'");
        }
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
        throw new LangException("Набор фраз с именем '$name' не найден");
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
            throw new LangException("Класс фабрики '{$this->config['factory']}' должен наследовать " . TextFactory::class);
        }
        $factory = new $this->config['factory']();
        $this->texts = $factory->buildTexts($this->config);
    }
}