<?php
/**
 * Date: 12.05.2020
 * Time: 7:52
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language;

/**
 * Class Texts
 * @package kradwhite\language
 */
class Texts
{
    /** @var array */
    private array $config;

    /** @var Text[] */
    private array $texts = [];

    /** @var TextFactory */
    private TextFactory $factory;

    /**
     * Texts constructor.
     * @param array $config
     * @throws LangException
     */
    public function __construct(array $config)
    {
        if (!isset($config['default']) || !$config['default']) {
            $config['default'] = 'default';
        }
        if (!isset($config['factory']) || !$config['factory']) {
            $config['factory'] = TextFactory::class;
        }
        if (!is_a($config['factory'], TextFactory::class, true)) {
            throw new LangException("Класс фабрики '{$config['factory']}' должен наследоваться " . TextFactory::class);
        }
        if (!isset($config['texts'])) {
            throw new LangException("Конфигурация ресурсов должна содержать массив 'texts' => ['type' => "
                . "'php|sql', 'names' => ['имена файлов без пути и расширения']]");
        }
        $this->factory = new $config['factory']();
        $this->config = $config;
    }

    /**
     * @param string $locale
     * @param string $name
     * @return Text
     * @throws LangException
     */
    public function getText(string $locale, string $name): Text
    {
        if (!$name) {
            $name = $this->config['default'];
        }
        if (!isset($this->texts[$name])) {
            $this->texts[$name] = $this->factory->build($this->config, $locale, $name);
        }
        return $this->texts[$name];
    }
}