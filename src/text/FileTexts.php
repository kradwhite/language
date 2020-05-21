<?php
/**
 * Date: 19.05.2020
 * Time: 21:25
 * Author: Artem Aleksandrov
 */

declare (strict_types=1);

namespace kradwhite\language\text;

use kradwhite\language\LangException;

/**
 * Class FileTexts
 * @package kradwhite\language\text
 */
class FileTexts extends Texts
{
    /** @var string */
    private string $directory;

    /**
     * FileTexts constructor.
     * @param string $type
     * @param array $names
     * @param TextFactory $factory
     * @param string $directory
     */
    public function __construct(string $type, array $names, TextFactory $factory, string $directory)
    {
        parent::__construct($type, $names, $factory);
        $this->directory = $directory;
    }

    /**
     * @param string $locale
     * @param string $name
     * @return FileText
     * @throws LangException
     */
    protected function buildText(string $locale, string $name): FileText
    {
        $filename = $this->directory . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . "$name.{$this->getType()}";
        if (!file_exists($filename)) {
            throw new LangException("Файл '$filename' не существует");
        }
        $texts = $this->getFileContent($filename, $this->getType());
        return $this->getFactory()->buildFileText($locale, $name, $texts);
    }

    /**
     * @param array $locales
     * @return void
     * @throws LangException
     */
    public function create(array $locales)
    {
        if (!$this->directory) {
            throw new LangException("Для ресурсов типа '{$this->getType()}' требуется имя директории 'directory' => 'path'");
        } else if (!file_exists($this->directory) && !mkdir($this->directory, 0775)) {
            throw new LangException("Ошибка создания директории '{$this->directory}'");
        }
        foreach ($locales as $locale) {
            $localePath = $this->directory . DIRECTORY_SEPARATOR . $locale;
            if (!file_exists($localePath)) {
                if (!mkdir($localePath, 0775)) {
                    throw new LangException("Ошибка создания директории '$localePath'");
                }
            }
            foreach ($this->getNames() as $name) {
                $filename = $localePath . DIRECTORY_SEPARATOR . "$name.php";
                if (!file_exists($filename)) {
                    if (!file_put_contents($filename, "<?php\n\nreturn [\n\t'error' => ['message'],\n];\n")) {
                        throw new LangException("Ошибка создания файла '$filename'");
                    }
                    chmod($filename, 0664);
                }
            }
        }
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
}