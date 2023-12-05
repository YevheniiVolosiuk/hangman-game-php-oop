<?php

declare(strict_types=1);


interface BaseParser
{
    public static function parseFile(string $filename): array;
}
