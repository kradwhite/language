<?php

use kradwhite\language\text\TextFactory;
use kradwhite\language\text\TextRepository;

return [
    'connection-not-found' => "For resources of type '%s', configuration is required 'connection' => []",
    'file-not-exist' => "File '%s' does not exist",
    'dir-key-not-found' => "For resources of type '%s', the directory name 'directory' => 'path' is required",
    'dir-create-error' => "Error creating directory '%s'",
    'file-create-error' => "Error creating file '%s'",
    'file-unknown-type' => "Unknown' %s ' file type '%s'",
    'phrase-not-found' => "In the '%s' language, a keyword with the '%s' ID was not found in the '%s' text",
    'phrase-params-wrong' => "Invalid parameters for the' %s 'keyword' %s'",
    'texts-not-found' => "The language configuration must contain the array 'texts' => []",
    'names-not-found' => "The language text configuration must contain an array of text names 'names' => []",
    'type-unknown' => "Unknown resource type '%s'",
    'repo-wrong' => "The repository class '%s ' must implement the interface '" . TextRepository::class . "'",
    'config-file-not-found' => "The file with the '%s ' configuration was not found",
    'dir-not-exist' => "The '%s ' directory does not exist",
    'dir-not-dir' => "'%s' is not a directory",
    'config-file-already-exist' => "The configuration file '%s ' already exists",
    'source-config-not-found' => "Source language configuration file not found '%s'",
    'config-copy-error' => "Error copying the '%s' configuration file",
    'text-not-found' => "A set of keywords named '%s' was not found",
    'factory-wrong' => "The factory class '%s ' must inherit " . TextFactory::class,
];
