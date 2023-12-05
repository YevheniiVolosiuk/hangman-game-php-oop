<?php

declare(strict_types=1);

require_once ROOT_DIR . '/src/Helpers/BaseParser.php';

/**
 * Helper class for parse data from json file
 */
class JsonParser implements BaseParser {

    /**
     * @throws Exception
     */
    public static function parseFile(string $filename): array {

        // Read the contents of the JSON file
        $jsonString = file_get_contents($filename);

        // Parse the JSON string
        $data = json_decode($jsonString);

        // Check for errors during parsing
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException('Error parsing JSON: ' . json_last_error_msg());
        }

        return $data;
    }
}

