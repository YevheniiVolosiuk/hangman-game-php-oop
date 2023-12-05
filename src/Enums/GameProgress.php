<?php

enum GameProgress
{
    public const PROGRESS_START = "
                  +---+
                  |   |
                      |
                      |
                      |
                      |
                =========";
    public const PROGRESS_1 = "
                  +---+
                  |   |
                  O   |
                      |
                      |
                      |
                =========";
    public const PROGRESS_2 = "
                  +---+
                  |   |
                  O   |
                  |   |
                      |
                      |
                =========";
    public const PROGRESS_3 = "
                  +---+
                  |   |
                  O   |
                 /|   |
                      |
                      |
                =========";
    public const PROGRESS_4 = "
                  +---+
                  |   |
                  O   |
                 /|\  |
                      |
                      |
                =========";
    public const PROGRESS_5 = "
                  +---+
                  |   |
                  O   |
                 /|\  |
                 /    |
                      |
                =========";
    public const PROGRESS_END = "
                  +---+
                  |   |
                  O   |
                 /|\  |
                 / \  |
                      |
                =========";

    public static function values(): array
    {
        return [
            self::PROGRESS_START,
            self::PROGRESS_1,
            self::PROGRESS_2,
            self::PROGRESS_3,
            self::PROGRESS_4,
            self::PROGRESS_5,
            self::PROGRESS_END,
        ];
    }
}
