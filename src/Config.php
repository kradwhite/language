<?php
/**
 * Date: 14.05.2020
 * Time: 19:25
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

use kradwhite\language\text\TextFactory;

class Config
{
    /** @var string */
    const Name = 'language.php';

    /** @var array */
    private array $config;

    /**
     * Config constructor.
     * @param array $config
     * @throws LangException
     */
    public function __construct(array $config)
    {
        if (!isset($config['locales'])) {
            $config['locales'] = ['ru'];
        }
        if (!isset($config['factory']) || !$config['factory']) {
            $config['factory'] = TextFactory::class;
        } else if (!is_a($config['factory'], TextFactory::class, true)) {
            throw new LangException("Класс фабрики '{$config['factory']}' должен наследоваться " . TextFactory::class);
        }
        if (!isset($config['default']) || !$config['default']) {
            $config['default'] = 'default';
        }
        if (!isset($config['texts'])) {
            throw new LangException("Конфигурация ресурсов должна содержать массив 'texts' => ['type' => "
                . "'php|database', 'names' => ['имена файлов без пути и расширения']]");
        }
        $config['factory'] = new $config['factory']();
        $this->config = $config;
    }

    /**
     * @return TextFactory
     */
    public function factory(): TextFactory
    {
        return $this->config['factory'];
    }

    /**
     * @return string
     */
    public function defaultName(): string
    {
        return $this->config['default'];
    }

    /**
     * @param string $name
     * @return array
     * @throws LangException
     */
    public function configByName(string $name): array
    {
        foreach ($this->config['texts'] as &$source) {
            if (in_array($name, $source['names'])) {
                return $source;
            }
        }
        throw new LangException("Имя ресурса '$name' не найдено");
    }

    /**
     * @param string $path
     * @throws LangException
     */
    public function create(string $path)
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
     * @return array
     */
    public function locales(): array
    {
        return $this->config['locales'];
    }

    /**
     * @return string
     */
    public function locale(): string
    {
        return $this->config['locales'][0];
    }

    /**
     * @param string $locale
     * @return bool
     */
    public function existLocale(string $locale): bool
    {
        return in_array($locale, $this->config['locales']);
    }

    /**
     * @return void
     * @throws LangException
     */
    public function init()
    {
        foreach ($this->config['texts'] as &$text) {
            $this->factory()->initTexts($text, $this->locales());
        }
    }
}