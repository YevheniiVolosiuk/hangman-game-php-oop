<?php

declare(strict_types=1);

/**
 * Public entry file for HangmanGame.
 */

const ROOT_DIR          = __DIR__ . '/..';
const STATIC_FOLDER_DIR = ROOT_DIR . '/resources/static';

require_once ROOT_DIR . '/src/Helpers/JsonParser.php';
require_once ROOT_DIR . '/src/Classes/HangmanGame.php';

$listOfWords = STATIC_FOLDER_DIR . '/nouns-nominative-words-it-en.json';

if (class_exists('HangmanGame')) {
    try {
        $parsedData = JsonParser::parseFile($listOfWords);
        $bootApp    = new HangmanGame($parsedData);
    } catch (Exception $error) {
        echo 'Error: ' . $error->getMessage();
    }
}
