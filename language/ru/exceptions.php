<?php

use kradwhite\language\text\TextFactory;
use kradwhite\language\text\TextRepository;

return [
	'connection-not-found' => "Для ресурсов типа '%s' требуется конфигурация 'connection' => []",
    'file-not-exist' => "Файл '%s' не существует",
    'dir-key-not-found' => "Для ресурсов типа '%s' требуется имя директории 'directory' => 'path'",
    'dir-create-error' => "Ошибка создания директории '%s'",
    'file-create-error' => "Ошибка создания файла '%s'",
    'file-unknown-type' => "Неизвестный тип '%s' файла '%s'",
    'phrase-not-found' => "В языке '%s' в тексте '%s' не найдена фраза с идентификатором '%s'",
    'phrase-params-wrong' => "Неверные параметры '%s' фразы '%s'",
    'texts-not-found' => "Конфигурация языков должна содержать массив 'texts' => []",
    'names-not-found' => "Конфигурация текста языка должна содержать массив имён текстов 'names' => []",
    'type-unknown' => "Неизвестный тип '%s' ресурсов",
    'repo-wrong' => "Класс репозитория '%s' должен реализовывать интерфейс '" . TextRepository::class . "'",
    'config-file-not-found' => "Файл с конфигурацией '%s' не найден",
    'dir-not-exist' => "Директория '%s' не существует",
    'dir-not-dir' => "'%s' не является директорией",
    'config-file-already-exist' => "Файл конфигурации '%s' уже существует",
    'source-config-not-found' => "Исходный файл конфигурации языков не найден '%s'",
    'config-copy-error' => "Ошибка копирования файла конфигурации '%s'",
    'text-not-found' => "Набор фраз с именем '%s' не найден",
    'factory-wrong' => "Класс фабрики '%s' должен наследовать " . TextFactory::class,
];
